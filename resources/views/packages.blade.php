<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packages | AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0a0f1a;
            color: #ffffff;
        }

        a {
            color: #ffffff;
            text-decoration: none;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: #000;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        .nav-links li a {
            color: #ffffff;
        }

        .nav-links li a.active {
            color: #f0f0f0ff;
        }

        .nav-icons a {
            margin-left: 15px;
            font-size: 18px;
        }

        .shop-hero {
            padding: 20px 40px 40px 40px;
            text-align: center;
        }

        .shop-title {
            font-size: 40px;
            margin-bottom: 30px;
        }

        .service-cards,
        .api-service-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .shop-card,
        .api-card {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: 0.3s;
        }

        .shop-card:hover,
        .api-card:hover {
            transform: translateY(-5px);
        }

        .shop-card img,
        .api-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-label {
            font-size: 20px;
            font-weight: bold;
            padding: 10px;
            text-align: center;
        }

        .card-content,
        .api-card-content {
            padding: 10px;
            text-align: center;
        }

        .card-content p,
        .api-card-content p {
            margin: 5px 0;
        }

        .add-btn {
            background: #000;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        .disabled-btn {
            background: #777;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
        }

        .api-section {
            padding: 40px;
        }

        .api-title {
            font-size: 30px;
            text-align: center;
            margin-bottom: 25px;
        }

        .api-price {
            color: #4da6ff;
            font-weight: bold;
        }

        .api-duration {
            color: #ccc;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">
        <a href="{{ url('/#home') }}">AUTOTECH</a>
    </div>

    <ul class="nav-links">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/#shop') }}" class="active">Shop</a></li>
        <li><a href="{{ url('/#services') }}">Services</a></li>
        <li><a href="{{ url('/booking') }}">Booking</a></li>
        <li><a href="{{ url('/reviews') }}">Reviews</a></li>
    </ul>

    <div class="nav-icons">
        <a href="{{ route('account') }}">👤</a>
        <a href="{{ route('cart') }}">
            🛒 <span id="cart-count">{{ collect(session('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<!-- PACKAGES -->
<section class="shop-hero">
    <h1 class="shop-title">Packages</h1>

    <div class="service-cards">
        @forelse($products as $product)
            <div class="shop-card">

                <a href="{{ route('product.show', $product->id) }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">

                    <div class="card-label">{{ $product->name }}</div>

                    <div class="card-content">
                        <p><strong>Rs {{ number_format($product->price, 2) }}</strong></p>
                        <p>{{ $product->description }}</p>
                        <p>Stock: {{ $product->stock }}</p>
                    </div>
                </a>

                <div style="padding-bottom:15px; text-align:center;">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add.ajax') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="type" value="product">
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <button type="submit" class="add-btn">Add to Cart</button>
                        </form>
                    @else
                        <button class="disabled-btn" disabled>Out of Stock</button>
                    @endif
                </div>

            </div>
        @empty
            <p>No packages available.</p>
        @endforelse
    </div>
</section>

<!-- API SECTION -->
<section class="api-section">
    <h2 class="api-title">Services Loaded from API</h2>
    <div id="api-services" class="api-service-cards"></div>
</section>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/cart.js') }}"></script>

<script>
$(document).ready(function () {

    $.ajax({
        url: "{{ url('/api/services') }}",
        type: "GET",
        success: function (data) {

            let html = '';

            data.forEach(function(service) {
                html += `
                    <div class="api-card">
                        <img src="/${service.image}" alt="${service.name}">
                        <div class="card-label">${service.name}</div>

                        <div class="api-card-content">
                            <p>${service.description}</p>
                            <p class="api-price">Rs ${service.price}</p>
                            <p class="api-duration">Duration: ${service.duration_minutes} mins</p>
                        </div>
                    </div>
                `;
            });

            $('#api-services').html(html);
        },

        error: function () {
            $('#api-services').html('<p style="color:red; text-align:center;">Error loading services</p>');
        }

    });

});
</script>

</body>
</html>