<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTOTECH - Reviews</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .reviews-page {
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
        }

        .reviews-hero {
            min-height: 100vh;
            background: url("{{ asset('images/ReviewWallapaper.png') }}") center/cover no-repeat;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 100px;
        }

        .reviews-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1;
        }

        .reviews-content {
            position: relative;
            z-index: 2;
            width: 90%;
            max-width: 1000px;
            margin-top: 120px;
            margin-bottom: 80px;
        }

        .page-title {
            font-size: 50px;
            text-align: center;
            margin-bottom: 40px;
        }

        .reviews-intro {
            text-align: center;
            color: #ddd;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .review-card {
            background: rgba(0, 0, 0, 0.65);
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 18px;
        }

        .review-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .review-user {
            font-weight: bold;
            color: #fff;
        }

        .review-product a {
            color: #00aaff;
            text-decoration: none;
            font-weight: bold;
        }

        .review-product a:hover {
            text-decoration: underline;
        }

        .review-date {
            color: #ccc;
            font-size: 13px;
        }

        .review-comment {
            color: #e5e5e5;
            margin: 10px 0;
            line-height: 1.7;
        }

        .review-stars {
            color: gold;
            font-size: 22px;
            letter-spacing: 2px;
        }

        .empty-review-card {
            text-align: center;
            color: #ddd;
            font-size: 18px;
            padding: 30px;
        }

        .reviews-note {
            margin-top: 30px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.65);
            border-radius: 12px;
            text-align: center;
            color: #ddd;
        }

        .reviews-note a {
            color: #00aaff;
            font-weight: bold;
            text-decoration: none;
        }

        .reviews-note a:hover {
            text-decoration: underline;
        }

        .reviews-pagination {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .reviews-pagination nav {
            background: rgba(0, 0, 0, 0.55);
            padding: 12px 16px;
            border-radius: 10px;
        }

        .reviews-pagination svg {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 36px;
            }

            .reviews-content {
                margin-top: 90px;
            }

            .review-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="reviews-page">

    <section class="reviews-hero">

        <nav class="navbar">
            <div class="logo">
                <a href="{{ url('/#home') }}">AUTOTECH</a>
            </div>

            <ul class="nav-links">
                <li><a href="{{ url('/#home') }}">Home</a></li>
                <li><a href="{{ url('/#shop') }}">Shop</a></li>
                <li><a href="{{ url('/#services') }}">Services</a></li>
                <li><a href="{{ url('/booking') }}">Booking</a></li>
                <li><a href="{{ route('reviews') }}" class="active">Reviews</a></li>
            </ul>

            <div class="nav-icons">
                
                <a href="{{ route('account') }}" title="Account">👤</a>
                <a href="{{ url('/cart') }}" title="Cart">🛒</a>
            </div>
        </nav>

        <div class="reviews-content">
            <h1 class="page-title">REVIEWS</h1>

            <p class="reviews-intro">
                See what customers are saying about our products.
                To leave a review, open a product page and submit your rating there.
            </p>

            @forelse($reviews as $review)
                <div class="review-card">
                    <div class="review-top">
                        <div class="review-user">
                            {{ $review->user->name ?? 'User' }}
                        </div>

                        <div class="review-product">
                            Product:
                            <a href="{{ route('product.show', $review->product->id) }}">
                                {{ $review->product->name ?? 'Product' }}
                            </a>
                        </div>

                        <div class="review-date">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>

                    <div class="review-comment">
                        {{ $review->comment ?: 'No written comment provided.' }}
                    </div>
                </div>
            @empty
                <div class="review-card empty-review-card">
                    No reviews have been submitted yet.
                </div>
            @endforelse

            @if($reviews->hasPages())
                <div class="reviews-pagination">
                    {{ $reviews->links() }}
                </div>
            @endif

            <div class="reviews-note">
                Want to leave a review? Go to a product page, log in to your account, and submit your review there.
            </div>
        </div>

    </section>

</body>
</html>