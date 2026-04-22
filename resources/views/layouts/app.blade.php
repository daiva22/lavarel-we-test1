<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AUTOTECH')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">
        <a href="{{ url('/') }}">AUTOTECH</a>
    </div>

    <ul class="nav-links">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/#shop') }}">Shop</a></li>
        <li><a href="{{ url('/#services') }}">Services</a></li>
        <li><a href="{{ url('/booking') }}">Booking</a></li>
        <li><a href="{{ url('/reviews') }}">Reviews</a></li>
       

    <div class="nav-icons">
        <a href="{{ url('/account') }}">👤</a>
        <a href="{{ url('/cart') }}"> 🛒</a>
    </ul>
    </div>
</nav>

<!-- PAGE CONTENT -->
@yield('content')

</body>
</html>