<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subcategory->name }} - AutoTech</title>

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
            font-size: 38px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #bdbdbd;
            font-size: 15px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: #141414;
            border: 1px solid #2b2b2b;
            border-radius: 16px;
            overflow: hidden;
            transition: 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        }

        .product-card:hover {
            transform: translateY(-6px);
            border-color: #d51340;
            box-shadow: 0 12px 28px rgba(213, 19, 64, 0.25);
        }

        .product-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .product-image-wrapper {
            width: 100%;
            height: 200px;
            background: #1d1d1d;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-content {
            padding: 15px;
        }

        .product-content h3 {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .product-content p {
            font-size: 14px;
            color: #bdbdbd;
            margin-bottom: 10px;
        }

        .price {
            color: #d51340;
            font-weight: bold;
            font-size: 16px;
        }

        .card-actions {
            padding: 0 15px 15px;
            text-align: center;
        }

        .cart-btn {
            background: #d51340;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .cart-btn:disabled {
            background: #777;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            border: 1px solid #2b2b2b;
            border-radius: 16px;
            background: #141414;
        }

        .empty-state h2 {
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #aaa;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 28px;
            }

            .product-image-wrapper {
                height: 180px;
            }
        }
    </style>
</head>
<body>

    <div class="page-wrapper">

        <div class="page-header">
            <h1>{{ $subcategory->name }}</h1>
            <p>{{ $subcategory->category }}</p>
        </div>

        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">

                        <a href="{{ route('product.show', $product->id) }}" class="product-link">
                            <div class="product-image-wrapper">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                            </div>

                            <div class="product-content">
                                <h3>{{ $product->name }}</h3>

                                <p>
                                    {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                                </p>

                                <div class="price">
                                    Rs {{ number_format($product->price, 2) }}
                                </div>
                            </div>
                        </a>

                        <div class="card-actions">
                            @if($product->stock > 0)
                                <form action="{{ route('cart.add.ajax') }}" method="POST" class="ajax-add-to-cart">
                                    @csrf
                                    <input type="hidden" name="type" value="product">
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <button type="submit" class="cart-btn">Add to Cart</button>
                                </form>
                            @else
                                <button type="button" class="cart-btn" disabled>Out of Stock</button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h2>No Products Found</h2>
                <p>There are no products available in this subcategory.</p>
            </div>
        @endif

    </div>
 

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>