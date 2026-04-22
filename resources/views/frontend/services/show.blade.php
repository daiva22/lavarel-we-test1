<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} - AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .service-detail-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .service-detail-box {
            background: #111;
            color: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        }

        .service-detail-image {
            width: 100%;
            height: 380px;
            object-fit: cover;
            display: block;
            background: #ddd;
        }

        .service-detail-no-image {
            width: 100%;
            height: 380px;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
        }

        .service-detail-content {
            padding: 30px;
        }

        .service-detail-box h1 {
            margin-bottom: 15px;
            font-size: 34px;
        }

        .service-detail-box h3 {
            margin-bottom: 20px;
            color: #f5c542;
            font-size: 20px;
        }

        .service-detail-box p {
            margin-bottom: 20px;
            line-height: 1.7;
            color: #ddd;
        }

        .service-info {
            font-size: 18px;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        .service-buttons a {
            display: inline-block;
            padding: 12px 18px;
            margin-right: 10px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-book {
            background: #f5c542;
            color: #000;
        }

        .btn-back {
            background: #333;
            color: #fff;
        }

        .service-reviews-section {
            margin-top: 30px;
            background: #111;
            color: #fff;
            border-radius: 14px;
            padding: 25px 30px 30px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        }

        .service-reviews-section h2 {
            margin-bottom: 20px;
            font-size: 28px;
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
            .service-detail-page {
                padding: 30px 15px;
            }

            .service-detail-image,
            .service-detail-no-image {
                height: 260px;
            }

            .service-detail-box h1 {
                font-size: 28px;
            }

            .review-card-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <div class="service-detail-page">
        <div class="service-detail-box">

            @if($service->image)
                <img
                    src="{{ asset($service->image) }}"
                    alt="{{ $service->name }}"
                    class="service-detail-image"
                >
            @else
                <div class="service-detail-no-image">
                    {{ $service->name }}
                </div>
            @endif

            <div class="service-detail-content">
                <h1>{{ $service->name }}</h1>
                <h3>Main Title: {{ $service->category->name ?? 'No Category' }}</h3>

                <p>
                    {{ $service->description ? $service->description : 'No description available for this service.' }}
                </p>

                <div class="service-info">
                    <strong>Price:</strong> Rs {{ number_format($service->price, 2) }}<br>
                    <strong>Duration:</strong> {{ $service->duration_minutes }} minutes<br>
                    <strong>Status:</strong> {{ $service->is_active ? 'Available' : 'Unavailable' }}
                </div>

                <div class="service-buttons">
                    <a href="{{ route('booking', ['service_id' => $service->id]) }}" class="btn-book">Book Now</a>
                    <a href="{{ route('services.front.category', $service->service_category_id) }}" class="btn-back">Back</a>
                </div>
            </div>
        </div>

        <div class="service-reviews-section">
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
                @forelse($service->reviews as $review)
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
                        No reviews yet for this service.
                    </div>
                @endforelse
            </div>

            @auth
                @php
                    $userAlreadyReviewed = $service->reviews->contains('user_id', auth()->id());
                @endphp

                @if(!$userAlreadyReviewed)
                    <div class="review-form-box">
                        <h3>Leave a Review</h3>

                        <form method="POST" action="{{ route('service.review.store', $service->id) }}">
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
                        You have already reviewed this service.
                    </div>
                @endif
            @else
                <div class="review-message-info">
                    Please <a href="{{ route('account') }}" style="color: #f5c542; font-weight: bold;">log in</a> to leave a review.
                </div>
            @endauth
        </div>
    </div>

</body>
</html>