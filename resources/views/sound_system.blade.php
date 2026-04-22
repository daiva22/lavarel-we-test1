<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sound System - AutoTech</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0b0b0b;
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .page-wrapper {
            min-height: 100vh;
            padding: 40px 20px 60px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 40px;
            margin-bottom: 10px;
            color: #fff;
        }

        .page-header p {
            color: #bdbdbd;
            font-size: 16px;
        }

        .subcategory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .subcategory-card {
            display: block;
            text-decoration: none;
            background: #141414;
            border: 1px solid #2b2b2b;
            border-radius: 16px;
            overflow: hidden;
            transition: 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        }

        .subcategory-card:hover {
            transform: translateY(-6px);
            border-color: #d51340;
            box-shadow: 0 12px 28px rgba(213, 19, 64, 0.25);
        }

        .subcategory-image-wrapper {
            width: 100%;
            height: 220px;
            background: #1d1d1d;
            overflow: hidden;
        }

        .subcategory-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: 0.4s ease;
        }

        .subcategory-card:hover .subcategory-image {
            transform: scale(1.05);
        }

        .subcategory-content {
            padding: 18px;
        }

        .subcategory-content h3 {
            font-size: 22px;
            color: #fff;
            margin-bottom: 8px;
        }

        .subcategory-content span {
            display: inline-block;
            color: #d51340;
            font-size: 14px;
            font-weight: bold;
            margin-top: 6px;
        }

        .empty-state {
            text-align: center;
            background: #141414;
            border: 1px solid #2b2b2b;
            border-radius: 16px;
            padding: 50px 20px;
            color: #bdbdbd;
        }

        .empty-state h2 {
            color: #fff;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .empty-state p {
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 30px;
            }

            .subcategory-image-wrapper {
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            .page-wrapper {
                padding: 25px 15px 40px;
            }

            .page-header h1 {
                font-size: 26px;
            }

            .subcategory-content h3 {
                font-size: 20px;
            }

            .subcategory-image-wrapper {
                height: 180px;
            }
        }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <div class="page-header">
            <h1>Sound System</h1>
            <p>Browse our sound system subcategories</p>
        </div>

        @if($subcategories->isNotEmpty())
            <div class="subcategory-grid">
                @foreach($subcategories as $subcategory)
                    <a href="{{ route('subcategory.products', $subcategory->id) }}" class="subcategory-card">
                        <div class="subcategory-image-wrapper">
                            @if($subcategory->image)
                                <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" class="subcategory-image">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="{{ $subcategory->name }}" class="subcategory-image">
                            @endif
                        </div>

                        <div class="subcategory-content">
                            <h3>{{ $subcategory->name }}</h3>
                            <span>View Products</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h2>No Subcategories Found</h2>
                <p>There are no sound system subcategories available right now.</p>
            </div>
        @endif
    </div>

</body>
</html>