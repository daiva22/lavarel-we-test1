<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination-wrapper svg {
            width: 16px;
            height: 16px;
        }

        .pagination-wrapper span,
        .pagination-wrapper a {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #333;
            background: #fff;
        }

        .pagination-wrapper .active span {
            background: #111;
            color: #fff;
            border-color: #111;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AutoTech</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.products.index') }}" class="active">Products</a>
        <a href="{{ route('admin.subcategories.index') }}">Subcategories</a>
        <a href="{{ route('admin.services.index') }}">Services</a>
        <a href="{{ route('admin.bookings.index') }}">Bookings</a>
        <a href="{{ route('home') }}">Back to Website</a>
    </div>

    <div class="main">
        <div class="topbar">Manage Products</div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:15px; flex-wrap:wrap;">
                <h3 style="margin:0;">All Products</h3>
                <a href="{{ route('admin.products.create') }}" class="action-link">Add Product</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>

                            <td>
                                <img
                                    src="{{ $product->image_url }}"
                                    alt="{{ $product->name }}"
                                    class="thumb"
                                >
                            </td>

                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->subcategory->name ?? 'No Subcategory' }}</td>
                            <td>Rs {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ ucfirst($product->status) }}</td>
                            <td>{{ $product->is_featured ? 'Yes' : 'No' }}</td>
                            <td>
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="edit-btn">Edit</a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="empty">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

</body>
</html>