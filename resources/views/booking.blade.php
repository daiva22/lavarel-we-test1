<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('/images/Booking_Background.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .booking-container {
            max-width: 760px;
            margin: 50px auto;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 8px;
            background: #fff;
            color: #000;
            font-size: 15px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn-book,
        .btn-login {
            display: inline-block;
            width: 100%;
            padding: 14px;
            background: #e63946;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            box-sizing: border-box;
        }

        .btn-book:hover,
        .btn-login:hover {
            background: #c1121f;
        }

        .btn-book:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert-success {
            background: #198754;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #dc3545;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .login-required-box {
            background: #222;
            border: 1px solid #444;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
        }

        .login-required-box p {
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.6;
        }

        .field-error {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 6px;
        }

        .helper-text {
            color: #bbb;
            font-size: 14px;
            margin-top: 6px;
            line-height: 1.5;
        }

        .slot-box {
            margin-top: 10px;
            padding: 16px;
            border: 1px solid #333;
            border-radius: 10px;
            background: #151515;
        }

        .slot-status {
            font-size: 14px;
            color: #ccc;
            margin-bottom: 12px;
        }

        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 10px;
        }

        .slot-btn {
            border: 1px solid #666;
            background: #fff;
            color: #111;
            border-radius: 8px;
            padding: 10px 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .slot-btn:hover {
            transform: translateY(-1px);
        }

        .slot-btn.active {
            background: #e63946;
            color: #fff;
            border-color: #e63946;
        }

        .selected-slot-preview {
            margin-top: 12px;
            font-size: 14px;
            color: #8ee28e;
            font-weight: bold;
        }

        .hidden-time-input {
            display: none;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <a class="logo" href="{{ url('/') }}">AUTOTECH</a>
    </div>

    <div class="nav-center">
        <ul class="nav-links">
            <li><a href="{{ url('/#home') }}">Home</a></li>
            <li><a href="{{ url('/#shop') }}">Shop</a></li>
            <li><a href="{{ url('/#services') }}">Services</a></li>
            <li><a href="{{ url('/booking') }}" class="active">Booking</a></li>
            <li><a href="{{ url('/reviews') }}">Reviews</a></li>
        </ul>
    </div>

    <div class="nav-right nav-icons">
        
        <a href="{{ url('/account') }}">👤</a>
        <a href="{{ url('/cart') }}">🛒</a>
    </div>
</nav>


<div class="booking-container">
    <h1>Book a Service</h1>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @auth
        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
            @csrf

            <div class="form-group">
                <label for="service_id">Select Service</label>
                <select name="service_id" id="service_id" required>
                    <option value="">-- Choose a Service --</option>
                    @foreach($services as $service)
                        <option
                            value="{{ $service->id }}"
                            {{ old('service_id', optional($selectedService)->id) == $service->id ? 'selected' : '' }}
                        >
                            {{ $service->name }} - Rs {{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} mins)
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="car_brand">Car Brand</label>
                <input
                    type="text"
                    name="car_brand"
                    id="car_brand"
                    value="{{ old('car_brand') }}"
                    placeholder="Example: Toyota"
                    required
                >
                @error('car_brand')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="car_model">Car Model</label>
                <input
                    type="text"
                    name="car_model"
                    id="car_model"
                    value="{{ old('car_model') }}"
                    placeholder="Example: Corolla"
                    required
                >
                @error('car_model')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="date">Booking Date</label>
                <input
                    type="text"
                    name="date"
                    id="date"
                    value="{{ $initialDate }}"
                    placeholder="Select a date"
                    autocomplete="off"
                    required
                >
                <div class="helper-text">
                    Closed dates and full dates are disabled automatically.
                </div>
                @error('date')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Available Time Slots</label>

                <input
                    type="hidden"
                    name="time"
                    id="time"
                    class="hidden-time-input"
                    value="{{ $initialTime }}"
                    required
                >

                <div class="slot-box">
                    <div class="slot-status" id="slotStatus">
                        Select a service first, then choose a date.
                    </div>

                    <div class="slot-grid" id="slotGrid"></div>

                    <div class="selected-slot-preview" id="selectedSlotPreview">
                        @if($initialTime)
                            Selected time: {{ $initialTime }}
                        @endif
                    </div>
                </div>

                @error('time')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea
                    name="notes"
                    id="notes"
                    placeholder="Extra details..."
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-book" id="submitButton">Book Now</button>
        </form>
    @else
        <div class="login-required-box">
            <p>
                You must be logged in before you can book a service.
            </p>
            <a href="{{ route('account') }}" class="btn-login">Login to Continue</a>
        </div>
    @endauth
</div>

@auth
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const serviceSelect = document.getElementById('service_id');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const slotGrid = document.getElementById('slotGrid');
    const slotStatus = document.getElementById('slotStatus');
    const selectedSlotPreview = document.getElementById('selectedSlotPreview');
    const submitButton = document.getElementById('submitButton');

    let datePickerInstance = null;
    let disabledDates = [];
    let selectedDate = @json($initialDate);
    let selectedTime = @json($initialTime);

    function setSubmitState() {
        submitButton.disabled = !(serviceSelect.value && dateInput.value && timeInput.value);
    }

    function clearSlots(message = 'Select a date to see available time slots.') {
        slotGrid.innerHTML = '';
        slotStatus.textContent = message;
        timeInput.value = '';
        selectedSlotPreview.textContent = '';
        setSubmitState();
    }

    function renderSlots(slots) {
        slotGrid.innerHTML = '';

        if (!slots.length) {
            slotStatus.textContent = 'No available time slots for this date.';
            timeInput.value = '';
            selectedSlotPreview.textContent = '';
            setSubmitState();
            return;
        }

        slotStatus.textContent = 'Click one available time slot.';

        slots.forEach(slot => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'slot-btn';
            button.textContent = slot.label;
            button.dataset.value = slot.value;

            if (selectedTime === slot.value) {
                button.classList.add('active');
                timeInput.value = slot.value;
                selectedSlotPreview.textContent = 'Selected time: ' + slot.label;
            }

            button.addEventListener('click', function () {
                document.querySelectorAll('.slot-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                timeInput.value = this.dataset.value;
                selectedTime = this.dataset.value;
                selectedSlotPreview.textContent = 'Selected time: ' + this.textContent;
                setSubmitState();
            });

            slotGrid.appendChild(button);
        });

        setSubmitState();
    }

    async function loadAvailability(date = null) {
        const serviceId = serviceSelect.value;

        clearSlots(serviceId ? 'Loading available slots...' : 'Select a service first, then choose a date.');

        if (!serviceId) {
            if (datePickerInstance) {
                datePickerInstance.clear();
                datePickerInstance.set('disable', []);
            }
            dateInput.value = '';
            selectedDate = null;
            selectedTime = null;
            return;
        }

        const url = new URL(@json(route('booking.availability')), window.location.origin);
        url.searchParams.set('service_id', serviceId);

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

            disabledDates = data.disabled_dates || [];
            initDatePicker(disabledDates);

            if (date) {
                renderSlots(data.slots || []);
            } else {
                clearSlots('Choose a date to see available time slots.');
            }
        } catch (error) {
            clearSlots('Could not load availability. Please try again.');
        }
    }

    function initDatePicker(disabledDatesList) {
        const currentValue = dateInput.value;

        if (datePickerInstance) {
            datePickerInstance.destroy();
        }

        datePickerInstance = flatpickr(dateInput, {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disable: disabledDatesList,
            defaultDate: currentValue || null,
            onChange: function(selectedDates, dateStr) {
                selectedDate = dateStr;
                selectedTime = null;
                timeInput.value = '';
                selectedSlotPreview.textContent = '';

                if (!dateStr) {
                    clearSlots('Choose a date to see available time slots.');
                    return;
                }

                loadAvailability(dateStr);
            }
        });
    }

    serviceSelect.addEventListener('change', function () {
        selectedTime = null;
        timeInput.value = '';
        selectedSlotPreview.textContent = '';

        if (datePickerInstance) {
            datePickerInstance.clear();
        }

        dateInput.value = '';
        selectedDate = null;

        loadAvailability();
    });

    document.getElementById('bookingForm').addEventListener('submit', function (event) {
        if (!serviceSelect.value || !dateInput.value || !timeInput.value) {
            event.preventDefault();
            alert('Please select a service, date, and time slot.');
        }
    });

    loadAvailability(selectedDate);

    if (selectedDate && serviceSelect.value) {
        loadAvailability(selectedDate);
    } else {
        initDatePicker([]);
        setSubmitState();
    }
</script>
@endauth

</body>
</html>