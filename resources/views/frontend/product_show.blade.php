<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .product-page {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .back-link-wrap {
            margin-bottom: 20px;
        }

        .back-link {
            text-decoration: none;
            color: #111;
            font-weight: bold;
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
            margin-bottom: 40px;
        }

        .product-image-box {
            background: #f8f8f8;
            border-radius: 14px;
            padding: 16px;
        }

        .product-image-box img {
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        .product-title {
            margin-bottom: 15px;
            font-size: 34px;
            line-height: 1.2;
        }

        .product-price {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
            color: hsla(0, 5%, 93%, 1.00);
        }

        .product-description {
            margin-bottom: 20px;
            line-height: 1.8;
            color: #ffffffff;
        }

        .product-meta {
            margin-bottom: 10px;
            color: #f6f6f6ff;
            line-height: 1.6;
        }

        .stock-in {
            color: green;
            font-weight: bold;
        }

        .stock-out {
            color: red;
            font-weight: bold;
        }

        .cart-action-wrap {
            margin-top: 30px;
        }

        .cart-btn,
        .cart-btn-disabled {
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: bold;
        }

        .cart-btn {
            background: #7c0e0e84;
            color: #fff;
            cursor: pointer;
        }

        .cart-btn-disabled {
            background: #777;
            color: #fff;
            cursor: not-allowed;
        }

        .service-prompt-box {
            margin-top: 40px;
            background: #111;
            color: #fff;
            border-radius: 14px;
            padding: 25px;
        }

        .service-prompt-box h2 {
            margin-bottom: 12px;
        }

        .service-prompt-box p {
            color: #ddd;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .service-prompt-actions {
            margin-bottom: 20px;
        }

        .service-prompt-actions a {
            display: inline-block;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .btn-primary {
            background: #f5c542;
            color: #000;
        }

        .btn-secondary {
            background: #333;
            color: #fff;
        }

        .service-suggestion-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-top: 18px;
        }

        .service-suggestion-card {
            background: #1b1b1b;
            border-radius: 12px;
            overflow: hidden;
        }

        .service-suggestion-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .service-suggestion-placeholder {
            height: 150px;
            background: #2a2a2a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .service-suggestion-content {
            padding: 15px;
        }

        .service-suggestion-content h4 {
            margin-bottom: 10px;
        }

        .service-suggestion-content p {
            margin-bottom: 12px;
            color: #ddd;
        }

        .service-suggestion-content a {
            display: inline-block;
            text-decoration: none;
            background: #f5c542;
            color: #000;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: bold;
        }

        .product-reviews-section {
            margin-top: 40px;
            padding: 25px;
            background: #111;
            color: #fff;
            border-radius: 14px;
        }

        .product-reviews-section h2 {
            margin-bottom: 20px;
        }

        .review-message-success,
        .review-message-error,
        .review-message-info {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .review-message-success {
            background: rgba(0, 128, 0, 0.20);
            color: #9fff9f;
        }

        .review-message-error {
            background: rgba(255, 0, 0, 0.20);
            color: #ffb0b0;
        }

        .review-message-info {
            background: rgba(255, 255, 255, 0.08);
            color: #e0e0e0;
        }

        .reviews-list {
            margin-bottom: 25px;
        }

        .review-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 15px;
        }

        .review-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .review-user {
            font-weight: bold;
            color: #fff;
        }

        .review-date {
            color: #cfcfcf;
            font-size: 13px;
        }

        .review-stars {
            color: gold;
            font-size: 22px;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .review-comment {
            color: #e5e5e5;
            line-height: 1.7;
            margin: 0;
        }

        .review-form-box {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 20px;
        }

        .review-form-box h3 {
            margin-bottom: 15px;
        }

        .review-form-box label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .review-form-box textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #2f2f2f;
            color: #fff;
            margin-bottom: 16px;
            resize: vertical;
            min-height: 110px;
            box-sizing: border-box;
        }

        .star-rating {
            direction: rtl;
            display: inline-flex;
            gap: 4px;
            font-size: 30px;
            margin-bottom: 18px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #888;
            cursor: pointer;
            margin: 0;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
        }

        .review-submit-btn {
            background: #f5c542;
            color: #000;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-title {
                font-size: 28px;
            }

            .product-page {
                margin: 20px auto;
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="product-page">

        <div class="back-link-wrap">
            <a href="{{ url()->previous() }}" class="back-link">← Back</a>
        </div>

        <div class="product-grid">

            <div class="product-image-box">
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                >
            </div>

            <div>
                <h1 class="product-title">{{ $product->name }}</h1>

                <p class="product-price">
                    Rs {{ number_format($product->price, 2) }}
                </p>

                <p class="product-description">
                    {{ $product->description }}
                </p>

                <p class="product-meta">
                    <strong>Category:</strong> {{ $product->category }}
                </p>

                @if($product->subcategory)
                    <p class="product-meta">
                        <strong>Subcategory:</strong> {{ $product->subcategory->name }}
                    </p>
                @endif

                <p class="product-meta">
                    <strong>Stock:</strong>
                    @if($product->stock > 0)
                        <span class="stock-in">In Stock ({{ $product->stock }})</span>
                    @else
                        <span class="stock-out">Out of Stock</span>
                    @endif
                </p>

                <div class="cart-action-wrap">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add.ajax') }}" method="POST" class="ajax-add-to-cart">
                            @csrf
                            <input type="hidden" name="type" value="product">
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <button type="submit" class="cart-btn">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="cart-btn-disabled">
                            Out of Stock
                        </button>
                    @endif
                </div>
            </div>

        </div>

        <div class="product-reviews-section">
            <h2>Customer Reviews</h2>

            @if(session('success'))
                <div class="review-message-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="review-message-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="review-message-error">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="reviews-list">
                @forelse($product->reviews as $review)
                    <div class="review-card">
                        <div class="review-card-top">
                            <div class="review-user">
                                {{ $review->user->name ?? 'User' }}
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

                        @if(!empty($review->comment))
                            <p class="review-comment">{{ $review->comment }}</p>
                        @else
                            <p class="review-comment">No written comment provided.</p>
                        @endif
                    </div>
                @empty
                    <div class="review-card">
                        No reviews yet for this product.
                    </div>
                @endforelse
            </div>

            @auth
                @php
                    $userAlreadyReviewed = $product->reviews->contains('user_id', auth()->id());
                @endphp

                @if(!$userAlreadyReviewed)
                    <div class="review-form-box">
                        <h3>Leave a Review</h3>

                        <form method="POST" action="{{ route('product.review.store', $product->id) }}">
                            @csrf

                            <label for="comment">Your Review</label>
                            <textarea
                                id="comment"
                                name="comment"
                                rows="4"
                                placeholder="Write your review here..."
                            >{{ old('comment') }}</textarea>

                            <label>Star Rating</label>
                            <div class="star-rating">
                                <input type="radio" name="rating" id="star5" value="5" {{ old('rating') == '5' ? 'checked' : '' }}>
                                <label for="star5">★</label>

                                <input type="radio" name="rating" id="star4" value="4" {{ old('rating') == '4' ? 'checked' : '' }}>
                                <label for="star4">★</label>

                                <input type="radio" name="rating" id="star3" value="3" {{ old('rating') == '3' ? 'checked' : '' }}>
                                <label for="star3">★</label>

                                <input type="radio" name="rating" id="star2" value="2" {{ old('rating') == '2' ? 'checked' : '' }}>
                                <label for="star2">★</label>

                                <input type="radio" name="rating" id="star1" value="1" {{ old('rating') == '1' ? 'checked' : '' }}>
                                <label for="star1">★</label>
                            </div>

                            <br>
                            <button type="submit" class="review-submit-btn">Submit Review</button>
                        </form>
                    </div>
                @else
                    <div class="review-message-info">
                        You have already reviewed this product.
                    </div>
                @endif
            @else
                <div class="review-message-info">
                    Please <a href="{{ route('account') }}" style="color: #f5c542; font-weight: bold;">log in</a> to leave a review.
                </div>
            @endauth
        </div>

        <div class="service-prompt-box">
            <h2>Do you also want a service with this product?</h2>
            <p>
                You can add a service booking together with your product choice.
                Select one of the services below or browse all services.
            </p>

            <div class="service-prompt-actions">
                <a href="{{ route('services.front.index') }}" class="btn-primary">View All Services</a>
                <a href="{{ route('booking') }}" class="btn-secondary">Go to Booking Page</a>
            </div>

            @if($suggestedServices->count())
                <div class="service-suggestion-list">
                    @foreach($suggestedServices as $service)
                        <div class="service-suggestion-card">
                            @if($service->image)
                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}">
                            @else
                                <div class="service-suggestion-placeholder">
                                    {{ $service->name }}
                                </div>
                            @endif

                            <div class="service-suggestion-content">
                                <h4>{{ $service->name }}</h4>
                                <p>
                                    Rs {{ number_format($service->price, 2) }}<br>
                                    {{ $service->duration_minutes }} mins
                                </p>
                                <a href="{{ route('booking', ['service_id' => $service->id]) }}">Book This Service</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</body>
</html>