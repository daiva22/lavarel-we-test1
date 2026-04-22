<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClosedDate;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private const SHOP_OPEN_TIME = '09:00';
    private const SHOP_CLOSE_TIME = '18:00';
    private const SLOT_INTERVAL_MINUTES = 30;
    private const LOOKAHEAD_DAYS = 90;

    public function index()
    {
        $bookings = Booking::with(['user', 'service'])
            ->latest()
            ->get();

        $closedDates = ClosedDate::orderBy('closed_on', 'asc')->get();

        return view('admin.bookings.index', compact('bookings', 'closedDates'));
    }

    public function availability(Request $request, $id)
    {
        $request->validate([
            'date' => 'nullable|date|after_or_equal:today',
        ]);

        $booking = Booking::with('service')->findOrFail($id);

        if (!$booking->service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found for this booking.',
            ], 404);
        }

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->date)->toDateString()
            : null;

        $closedDates = ClosedDate::pluck('closed_on')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->values()
            ->all();

        $fullDates = $this->getFullDatesForBooking($booking);

        $slots = [];
        if ($selectedDate) {
            $slots = $this->buildAvailableSlotsForBooking($booking, $selectedDate);
        }

        return response()->json([
            'success' => true,
            'closed_dates' => $closedDates,
            'full_dates' => $fullDates,
            'disabled_dates' => array_values(array_unique(array_merge($closedDates, $fullDates))),
            'slots' => $slots,
        ]);
    }

    public function approve($id)
    {
        $booking = Booking::with('service')->findOrFail($id);

        if ($this->isClosedDate($booking->start_at)) {
            return redirect()->back()->withErrors([
                'approve' => 'This booking cannot be approved because the selected date is closed.',
            ]);
        }

        if ($this->hasBookingConflict(
            Carbon::parse($booking->start_at),
            $booking->service ? (int) $booking->service->duration_minutes : 0,
            $booking->id
        )) {
            return redirect()->back()->withErrors([
                'approve' => 'This booking cannot be approved because the selected time conflicts with another booking.',
            ]);
        }

        $booking->status = 'approved';
        $booking->save();

        return redirect()->back()->with('success', 'Booking approved');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();

        return redirect()->back()->with('success', 'Booking rejected');
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::with('service')->findOrFail($id);

        if (!$booking->service) {
            return redirect()->back()->withErrors([
                'reschedule' => 'Service not found for this booking.',
            ])->withInput();
        }

        $newDate = Carbon::parse($request->date)->toDateString();

        if ($this->isClosedDate($newDate)) {
            return redirect()->back()->withErrors([
                'reschedule' => 'You cannot reschedule a booking to a closed date.',
            ])->withInput();
        }

        $newStartAt = Carbon::parse($request->date . ' ' . $request->time . ':00');

        if ($newStartAt->lt(now())) {
            return redirect()->back()->withErrors([
                'reschedule' => 'You cannot reschedule to a past time.',
            ])->withInput();
        }

        $availableSlots = collect($this->buildAvailableSlotsForBooking($booking, $newDate))
            ->pluck('value')
            ->all();

        if (!in_array($request->time, $availableSlots, true)) {
            return redirect()->back()->withErrors([
                'reschedule' => 'That time slot is not available anymore.',
            ])->withInput();
        }

        $booking->start_at = $newStartAt;
        $booking->status = 'pending';
        $booking->notes = $request->notes;
        $booking->save();

        return redirect()->back()->with('success', 'Booking rescheduled');
    }

    public function storeClosedDate(Request $request)
    {
        $request->validate([
            'closed_on' => [
                'required',
                'date',
                'after_or_equal:today',
                Rule::unique('closed_dates', 'closed_on'),
            ],
            'reason' => 'nullable|string|max:255',
        ], [
            'closed_on.unique' => 'This date is already marked as closed.',
            'closed_on.after_or_equal' => 'You can only close today or a future date.',
        ]);

        ClosedDate::create([
            'closed_on' => $request->closed_on,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Closed date added successfully.');
    }

    public function destroyClosedDate($id)
    {
        $closedDate = ClosedDate::findOrFail($id);
        $closedDate->delete();

        return redirect()->back()->with('success', 'Closed date reopened successfully.');
    }

    private function isClosedDate($dateTime): bool
    {
        $date = Carbon::parse($dateTime)->toDateString();

        return ClosedDate::whereDate('closed_on', $date)->exists();
    }

    private function getFullDatesForBooking(Booking $booking): array
    {
        if (!$booking->service) {
            return [];
        }

        $dates = [];
        $start = Carbon::today();
        $end = Carbon::today()->addDays(self::LOOKAHEAD_DAYS);

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $dateString = $date->format('Y-m-d');

            if ($this->isClosedDate($dateString)) {
                continue;
            }

            $slots = $this->buildAvailableSlotsForBooking($booking, $dateString);

            if (count($slots) === 0) {
                $dates[] = $dateString;
            }
        }

        return $dates;
    }

    private function buildAvailableSlotsForBooking(Booking $booking, string $date): array
    {
        if (!$booking->service || $this->isClosedDate($date)) {
            return [];
        }

        $dayOpen = Carbon::parse($date . ' ' . self::SHOP_OPEN_TIME . ':00');
        $dayClose = Carbon::parse($date . ' ' . self::SHOP_CLOSE_TIME . ':00');

        $serviceDuration = (int) $booking->service->duration_minutes;
        $lastPossibleStart = (clone $dayClose)->subMinutes($serviceDuration);

        if ($lastPossibleStart->lt($dayOpen)) {
            return [];
        }

        $slots = [];
        $cursor = clone $dayOpen;

        while ($cursor->lte($lastPossibleStart)) {
            if (
                Carbon::parse($date)->isToday() &&
                $cursor->lt(now()->copy()->addMinutes(self::SLOT_INTERVAL_MINUTES))
            ) {
                $cursor->addMinutes(self::SLOT_INTERVAL_MINUTES);
                continue;
            }

            if (!$this->hasBookingConflict($cursor, $serviceDuration, $booking->id)) {
                $slots[] = [
                    'value' => $cursor->format('H:i'),
                    'label' => $cursor->format('H:i'),
                ];
            }

            $cursor->addMinutes(self::SLOT_INTERVAL_MINUTES);
        }

        return $slots;
    }

    private function hasBookingConflict(Carbon $newStart, int $durationMinutes, $ignoreBookingId = null): bool
    {
        if (!$durationMinutes || $durationMinutes < 1) {
            return false;
        }

        $newEnd = (clone $newStart)->addMinutes($durationMinutes);

        $existingBookings = Booking::with('service')
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_at', $newStart->toDateString())
            ->when($ignoreBookingId, function ($query) use ($ignoreBookingId) {
                $query->where('id', '!=', $ignoreBookingId);
            })
            ->get();

        foreach ($existingBookings as $existingBooking) {
            if (!$existingBooking->service || !$existingBooking->start_at) {
                continue;
            }

            $existingStart = Carbon::parse($existingBooking->start_at);
            $existingEnd = (clone $existingStart)
                ->addMinutes((int) $existingBooking->service->duration_minutes);

            $hasOverlap = $newStart->lt($existingEnd) && $newEnd->gt($existingStart);

            if ($hasOverlap) {
                return true;
            }
        }

        return false;
    }
}