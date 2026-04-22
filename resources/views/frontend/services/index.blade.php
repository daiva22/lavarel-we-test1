<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .services-page {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .services-page h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .category-card {
            display: block;
            background: #111;
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <div class="services-page">
        <h1>Our Services</h1>

        @if($categories->count())
            <div class="category-grid">
                @foreach($categories as $category)
                    <a href="{{ route('services.front.category', $category->id) }}" class="category-card">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @else
            <p class="empty-message">No service categories available right now.</p>
        @endif
    </div>

</body>
</html>