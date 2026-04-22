<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessories | AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

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
        
        <a href="{{ url('/register') }}" title="Account">👤</a>
        <a href="{{ url('/cart') }}" title="Cart">
            🛒 <span id="cart-count">{{ collect(session('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<section class="shop-hero">
    <h1 class="shop-title">Accessories</h1>

    <div id="cart-message" style="display:none; position:fixed; top:20px; right:20px; background:#28a745; color:#fff; padding:12px 18px; border-radius:8px; z-index:9999;">
        Added to cart successfully
    </div>

    <div class="service-cards">
        @forelse($products as $product)
            <div class="shop-card">

                <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit; display:block;">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">

                    <div class="card-label">{{ $product->name }}</div>

                    <div style="padding: 10px; text-align: center; color: white;">
                        <p><strong>Rs {{ number_format($product->price, 2) }}</strong></p>
                        <p>{{ $product->description }}</p>
                        <p>Stock: {{ $product->stock }}</p>
                    </div>
                </a>

                <div style="padding: 10px; text-align: center;">
                    @if($product->stock > 0)
                        <button
                            type="button"
                            class="ajax-add-to-cart-btn"
                            data-url="{{ route('cart.add.ajax') }}"
                            data-type="product"
                            data-id="{{ $product->id }}"
                            data-token="{{ csrf_token() }}"
                            style="background:#000;color:#fff;padding:10px 18px;border:none;border-radius:8px;cursor:pointer;">
                            Add to Cart
                        </button>
                    @else
                        <button
                            type="button"
                            disabled
                            style="background:#777;color:#fff;padding:10px 18px;border:none;border-radius:8px;">
                            Out of Stock
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <p style="text-align:center; color:white;">No accessories available.</p>
        @endforelse
    </div>
</section>

<script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>
</body>
</html>