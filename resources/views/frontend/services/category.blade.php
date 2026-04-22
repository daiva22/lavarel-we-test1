<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .service-page {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-page h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .service-box {
            background: #111;
            color: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .service-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            background: #ddd;
        }

        .service-no-image {
            width: 100%;
            height: 220px;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
        }

        .service-content {
            padding: 20px;
        }

        .service-box h3 {
            margin-bottom: 10px;
            font-size: 22px;
        }

        .service-box p {
            margin-bottom: 10px;
            color: #ddd;
            line-height: 1.6;
        }

        .service-meta {
            margin-top: 15px;
            font-size: 15px;
            color: #f5c542;
            line-height: 1.7;
        }

        .service-actions {
            margin-top: 20px;
        }

        .service-actions a {
            display: inline-block;
            padding: 10px 16px;
            background: #f5c542;
            color: #000;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            margin-top: 40px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="service-page">
        <a href="{{ url('/#services') }}" class="back-link">← Back to Services</a>

        <h1>{{ $category->name }}</h1>

        @if($category->services->count())
            <div class="service-grid">
                @foreach($category->services as $service)
                    <div class="service-box">

                        @if($service->image)
                            <img
                                src="{{ asset($service->image) }}"
                                alt="{{ $service->name }}"
                                class="service-image"
                            >
                        @else
                            <div class="service-no-image">
                                {{ $service->name }}
                            </div>
                        @endif

                        <div class="service-content">
                            <h3>{{ $service->name }}</h3>

                            <p>
                                {{ $service->description ? $service->description : 'No description available.' }}
                            </p>

                            <div class="service-meta">
                                Price: Rs {{ number_format($service->price, 2) }}<br>
                                Duration: {{ $service->duration_minutes }} min
                            </div>

                            <div class="service-actions">
                                <a href="{{ route('service.show', $service->id) }}">View Service</a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <p class="empty-message">No services available in this category right now.</p>
        @endif
    </div>

</body>
</html>