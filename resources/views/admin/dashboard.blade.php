<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - AutoTech</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

<div class="sidebar">
    <h2>AutoTech</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}">Products</a>
    <a href="{{ route('admin.subcategories.index') }}">Subcategories</a>
    <a href="{{ route('admin.services.index') }}">Services</a>
    <a href="{{ route('admin.bookings.index') }}">Bookings</a>
    <a href="{{ route('home') }}">Back to Website</a>

</div>

<div class="main">

    <div class="topbar">
        Welcome Admin
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Bookings</h3>
            <p>{{ $totalBookings }}</p>
        </div>

        <div class="card">
            <h3>Total Products</h3>
            <p>{{ $totalProducts }}</p>
        </div>

        <div class="card">
            <h3>Total Services</h3>
            <p>{{ $totalServices }}</p>
        </div>
    </div>

    <h2 class="section-title">Recent Bookings</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Service</th>
            <th>Car</th>
            <th>Date</th>
            <th>Status</th>
        </tr>

        @forelse($recentBookings as $booking)
        <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->user->name }}</td>
            <td>{{ $booking->user->name }}</td>
            <td>{{ $booking->car_brand }} {{ $booking->car_model }}</td>
            <td>{{ $booking->start_at }}</td>
            <td>
                <span class="status {{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No bookings found</td>
        </tr>
        @endforelse
    </table>

</div>

</body>
</html>