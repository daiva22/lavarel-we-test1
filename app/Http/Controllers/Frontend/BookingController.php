<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClosedDate;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private const SHOP_OPEN_TIME = '09:00';
    private const SHOP_CLOSE_TIME = '18:00';
    private const SLOT_INTERVAL_MINUTES = 30;
    private const LOOKAHEAD_DAYS = 90;

    public function create(Request $request)
    {
        $services = Service::with('category')
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->latest()
            ->get();

        $selectedService = null;

        if ($request->filled('service_id')) {
            $selectedService = Service::with('category')
                ->where('is_active', true)
                ->whereHas('category', function ($query) {
                    $query->where('is_active', true);
                })
                ->find($request->service_id);
        }

        $initialServiceId = old('service_id', optional($selectedService)->id);
        $initialDate = old('date');
        $initialTime = old('time');

        return view('booking', compact(
            'services',
            'selectedService',
            'initialServiceId',
            'initialDate',
            'initialTime'
        ));
    }

    public function availability(Request $request)
    {
        $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'date' => 'nullable|date|after_or_equal:today',
        ]);

        $service = $this->findAvailableService((int) $request->service_id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Selected service is not available.',
            ], 404);
        }

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->date)->toDateString()
            : null;

        $closedDates = ClosedDate::pluck('closed_on')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->values()
            ->all();

        $fullDates = $this->getFullDates($service);

        $slots = [];
        if ($selectedDate) {
            $slots = $this->buildAvailableSlots($service, $selectedDate);
        }

        return response()->json([
            'success' => true,
            'closed_dates' => $closedDates,
            'full_dates' => $fullDates,
            'disabled_dates' => array_values(array_unique(array_merge($closedDates, $fullDates))),
            'slots' => $slots,
            'shop_open_time' => self::SHOP_OPEN_TIME,
            'shop_close_time' => self::SHOP_CLOSE_TIME,
            'slot_interval_minutes' => self::SLOT_INTERVAL_MINUTES,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()
                ->route('account')
                ->with('error', 'Please log in to book a service.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'car_brand' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $service = $this->findAvailableService((int) $request->service_id);

        if (!$service) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Selected service is not available.');
        }

        $bookingDate = Carbon::parse($request->date)->toDateString();

        if ($this->isClosedDate($bookingDate)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'This date is closed and cannot be booked.');
        }

        $newStart = Carbon::parse($request->date . ' ' . $request->time . ':00');

        if ($newStart->lt(now())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You cannot book a past time.');
        }

        $availableSlots = collect($this->buildAvailableSlots($service, $bookingDate))
            ->pluck('value')
            ->all();

        if (!in_array($request->time, $availableSlots, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'That time slot is no longer available. Please choose another slot.');
        }

        Booking::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'car_brand' => $request->car_brand,
            'car_model' => $request->car_model,
            'start_at' => $newStart->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('booking', ['service_id' => $service->id])
            ->with('success', 'Booking submitted successfully.');
    }

    private function findAvailableService(int $serviceId): ?Service
    {
        return Service::with('category')
            ->where('id', $serviceId)
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->first();
    }

    private function isClosedDate(string $date): bool
    {
        return ClosedDate::whereDate('closed_on', $date)->exists();
    }

    private function getFullDates(Service $service): array
    {
        $dates = [];

        $start = Carbon::today();
        $end = Carbon::today()->addDays(self::LOOKAHEAD_DAYS);

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $dateString = $date->format('Y-m-d');

            if ($this->isClosedDate($dateString)) {
                continue;
            }

            $slots = $this->buildAvailableSlots($service, $dateString);

            if (count($slots) === 0) {
                $dates[] = $dateString;
            }
        }

        return $dates;
    }

    private function buildAvailableSlots(Service $service, string $date): array
    {
        if ($this->isClosedDate($date)) {
            return [];
        }

        $dateCarbon = Carbon::parse($date);

        $dayOpen = Carbon::parse($date . ' ' . self::SHOP_OPEN_TIME . ':00');
        $dayClose = Carbon::parse($date . ' ' . self::SHOP_CLOSE_TIME . ':00');

        $serviceDuration = (int) $service->duration_minutes;
        $lastPossibleStart = (clone $dayClose)->subMinutes($serviceDuration);

        if ($lastPossibleStart->lt($dayOpen)) {
            return [];
        }

        $slots = [];
        $cursor = clone $dayOpen;

        while ($cursor->lte($lastPossibleStart)) {
            if (
                $dateCarbon->isToday() &&
                $cursor->lt(now()->copy()->addMinutes(self::SLOT_INTERVAL_MINUTES))
            ) {
                $cursor->addMinutes(self::SLOT_INTERVAL_MINUTES);
                continue;
            }

            if (!$this->hasBookingConflict($cursor, $serviceDuration)) {
                $slots[] = [
                    'value' => $cursor->format('H:i'),
                    'label' => $cursor->format('H:i'),
                ];
            }

            $cursor->addMinutes(self::SLOT_INTERVAL_MINUTES);
        }

        return $slots;
    }

    private function hasBookingConflict(Carbon $newStart, int $durationMinutes): bool
    {
        $newEnd = (clone $newStart)->addMinutes($durationMinutes);

        $existingBookings = Booking::with('service')
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_at', $newStart->toDateString())
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