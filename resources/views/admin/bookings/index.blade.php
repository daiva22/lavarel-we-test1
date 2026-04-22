<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .page-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }

        .page-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .inline-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 15px;
        }

        .inline-form input[type="date"],
        .inline-form input[type="text"],
        .inline-form input[type="datetime-local"] {
            padding: 10px 12px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            min-height: 42px;
        }

        .inline-form input[type="date"] {
            min-width: 180px;
        }

        .inline-form input[type="text"] {
            min-width: 260px;
        }

        .btn-primary,
        .btn-delete,
        .btn-approve,
        .btn-reject,
        .btn-reschedule,
        .slot-btn {
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary {
            background: #111827;
            color: #fff;
        }

        .btn-delete {
            background: #dc2626;
            color: #fff;
        }

        .closed-dates-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .closed-dates-table th,
        .closed-dates-table td,
        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #ececec;
            text-align: left;
            vertical-align: top;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .muted-text {
            color: #666;
            font-size: 14px;
        }

        .actions-cell form {
            margin-bottom: 10px;
        }

        .reschedule-box {
            margin-top: 10px;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fafafa;
            min-width: 320px;
        }

        .reschedule-box label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        .reschedule-box input[type="text"],
        .reschedule-box textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d0d0d0;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .reschedule-box textarea {
            min-height: 70px;
            resize: vertical;
        }

        .slot-status {
            margin: 10px 0 10px;
            font-size: 14px;
            color: #555;
        }

        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: 8px;
            margin-top: 8px;
        }

        .slot-btn {
            background: #fff;
            border: 1px solid #bbb;
            color: #111;
        }

        .slot-btn.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
        }

        .selected-slot-preview {
            margin-top: 10px;
            font-size: 14px;
            color: #0f5132;
            font-weight: 600;
        }

        .hidden-input {
            display: none;
        }

        .small-note {
            font-size: 13px;
            color: #666;
            margin-top: 6px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>AutoTech</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="#">Products</a>
    <a href="{{ route('admin.services.index') }}">Services</a>
    <a href="{{ route('admin.bookings.index') }}">Bookings</a>
    <a href="{{ route('home') }}">Back to Website</a>

    
</div>

<div class="main">

    <div class="topbar">
        Manage Bookings
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            <strong>Please fix these errors:</strong>
            <ul style="margin:10px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-section">
        <h3>Closed Dates</h3>
        <p class="muted-text">
            Block a full day for holidays, maintenance, or any date when bookings should not be allowed.
        </p>

        <form action="{{ route('admin.bookings.closed-dates.store') }}" method="POST" class="inline-form">
            @csrf
            <input type="date" name="closed_on" value="{{ old('closed_on') }}" required>
            <input type="text" name="reason" value="{{ old('reason') }}" placeholder="Reason (optional)">
            <button type="submit" class="btn-primary">Add Closed Date</button>
        </form>

        <table class="closed-dates-table">
            <tr>
                <th>Date</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>

            @forelse($closedDates as $closedDate)
                <tr>
                    <td>{{ $closedDate->closed_on->format('Y-m-d') }}</td>
                    <td>{{ $closedDate->reason ?: '-' }}</td>
                    <td>
                        <form action="{{ route('admin.bookings.closed-dates.destroy', $closedDate->id) }}" method="POST" onsubmit="return confirm('Reopen this date?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Reopen</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No closed dates added yet.</td>
                </tr>
            @endforelse
        </table>
    </div>

    <div class="page-section">
        <h3>All Bookings</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Service</th>
                <th>Car</th>
                <th>Date</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>

            @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->user ? $booking->user->name : 'Test User' }}</td>
                <td>{{ $booking->service ? $booking->service->name : 'N/A' }}</td>
                <td>{{ $booking->car_brand ?? '-' }} {{ $booking->car_model ?? '' }}</td>
                <td>{{ $booking->start_at ? $booking->start_at->format('Y-m-d H:i') : '-' }}</td>
                <td>
                    <span class="status {{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>{{ $booking->notes ?? '-' }}</td>
                <td class="actions-cell">
                    <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn-approve">Approve</button>
                    </form>

                    <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn-reject">Reject</button>
                    </form>

                    <form
                        action="{{ route('admin.bookings.reschedule', $booking->id) }}"
                        method="POST"
                        class="reschedule-form reschedule-box"
                        data-booking-id="{{ $booking->id }}"
                    >
                        @csrf

                        <label for="date_{{ $booking->id }}">Reschedule Date</label>
                        <input
                            type="text"
                            id="date_{{ $booking->id }}"
                            class="reschedule-date"
                            name="date"
                            placeholder="Select date"
                            autocomplete="off"
                            required
                        >

                        <div class="small-note">
                            Closed dates and full dates are disabled automatically.
                        </div>

                        <label>Available Time Slots</label>
                        <input type="hidden" name="time" class="reschedule-time hidden-input" required>

                        <div class="slot-status">Select a date to load time slots.</div>
                        <div class="slot-grid"></div>
                        <div class="selected-slot-preview"></div>

                        <label for="notes_{{ $booking->id }}">Notes</label>
                        <textarea
                            name="notes"
                            id="notes_{{ $booking->id }}"
                            placeholder="Add note"
                        ></textarea>

                        <button type="submit" class="btn-reschedule">Reschedule</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No bookings found</td>
            </tr>
            @endforelse
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.querySelectorAll('.reschedule-form').forEach(function(form) {
        const bookingId = form.dataset.bookingId;
        const dateInput = form.querySelector('.reschedule-date');
        const timeInput = form.querySelector('.reschedule-time');
        const slotGrid = form.querySelector('.slot-grid');
        const slotStatus = form.querySelector('.slot-status');
        const selectedPreview = form.querySelector('.selected-slot-preview');
        let datePickerInstance = null;

        function clearSlots(message = 'Select a date to load time slots.') {
            slotGrid.innerHTML = '';
            slotStatus.textContent = message;
            timeInput.value = '';
            selectedPreview.textContent = '';
        }

        function renderSlots(slots) {
            slotGrid.innerHTML = '';

            if (!slots.length) {
                slotStatus.textContent = 'No available slots for this date.';
                timeInput.value = '';
                selectedPreview.textContent = '';
                return;
            }

            slotStatus.textContent = 'Click one available time slot.';

            slots.forEach(function(slot) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'slot-btn';
                button.textContent = slot.label;
                button.dataset.value = slot.value;

                button.addEventListener('click', function() {
                    form.querySelectorAll('.slot-btn').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    timeInput.value = this.dataset.value;
                    selectedPreview.textContent = 'Selected time: ' + this.textContent;
                });

                slotGrid.appendChild(button);
            });
        }

        async function loadAvailability(date = null) {
            clearSlots(date ? 'Loading available slots...' : 'Select a date to load time slots.');

            const url = new URL(`/admin/bookings/${bookingId}/availability`, window.location.origin);

            if (date) {
                url.searchParams.set('date', date);
            }

            try {
                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    clearSlots(data.message || 'Could not load availability.');
                    return;
                }

                initDatePicker(data.disabled_dates || []);

                if (date) {
                    renderSlots(data.slots || []);
                } else {
                    clearSlots('Select a date to load time slots.');
                }
            } catch (error) {
                clearSlots('Could not load availability.');
            }
        }

        function initDatePicker(disabledDates) {
            const currentValue = dateInput.value;

            if (datePickerInstance) {
                datePickerInstance.destroy();
            }

            datePickerInstance = flatpickr(dateInput, {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disable: disabledDates,
                defaultDate: currentValue || null,
                onChange: function(selectedDates, dateStr) {
                    timeInput.value = '';
                    selectedPreview.textContent = '';

                    if (!dateStr) {
                        clearSlots('Select a date to load time slots.');
                        return;
                    }

                    loadAvailability(dateStr);
                }
            });
        }

        form.addEventListener('submit', function(event) {
            if (!dateInput.value || !timeInput.value) {
                event.preventDefault();
                alert('Please choose a date and click a time slot.');
            }
        });

        loadAvailability();
    });
</script>

</body>
</html>