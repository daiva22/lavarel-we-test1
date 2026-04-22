<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
        <div class="topbar">Edit Product</div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <h3>Update Product</h3>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label for="name">Product Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $product->name) }}"
                    required
                >

                <label for="description">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="5"
                >{{ old('description', $product->description) }}</textarea>

                <label for="price">Price</label>
                <input
                    type="number"
                    name="price"
                    id="price"
                    step="0.01"
                    value="{{ old('price', $product->price) }}"
                    required
                >

                <label for="stock">Stock</label>
                <input
                    type="number"
                    name="stock"
                    id="stock"
                    value="{{ old('stock', $product->stock) }}"
                    required
                >

                <label for="subcategory_id">Subcategory</label>
                <select name="subcategory_id" id="subcategory_id" required>
                    @foreach($subcategories as $subcategory)
                        <option
                            value="{{ $subcategory->id }}"
                            {{ (string) old('subcategory_id', $product->subcategory_id) === (string) $subcategory->id ? 'selected' : '' }}
                        >
                            {{ $subcategory->category }} → {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>

                @if($product->image)
                    <label>Current Image</label>
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="thumb"
                        style="display:block; margin-bottom: 18px;"
                    >
                @endif

                <label for="image">Change Image</label>
                <input type="file" name="image" id="image">

                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <div class="checkbox-wrap">
                    <input
                        type="checkbox"
                        name="is_featured"
                        id="is_featured"
                        value="1"
                        {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                    >
                    <label for="is_featured">Featured Product</label>
                </div>

                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>

</body>
</html>