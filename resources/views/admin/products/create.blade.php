<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - AutoTech</title>
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
        <div class="topbar">Add Product</div>

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
            <h3>Add New Product</h3>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="name">Product Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    placeholder="Example: JBL Speaker"
                    required
                >

                <label for="description">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    placeholder="Enter product description"
                >{{ old('description') }}</textarea>

                <label for="price">Price</label>
                <input
                    type="number"
                    name="price"
                    id="price"
                    step="0.01"
                    value="{{ old('price') }}"
                    placeholder="Example: 2500"
                    required
                >

                <label for="stock">Stock</label>
                <input
                    type="number"
                    name="stock"
                    id="stock"
                    value="{{ old('stock') }}"
                    placeholder="Example: 10"
                    required
                >

                <label for="subcategory_id">Subcategory</label>
                <select name="subcategory_id" id="subcategory_id" required>
                    <option value="">Select Subcategory</option>
                    @foreach($subcategories as $subcategory)
                        <option
                            value="{{ $subcategory->id }}"
                            {{ (string) old('subcategory_id', $selectedSubcategoryId) === (string) $subcategory->id ? 'selected' : '' }}
                        >
                            {{ $subcategory->category }} → {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>

                <label for="image">Product Image</label>
                <input type="file" name="image" id="image">

                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <div class="checkbox-wrap">
                    <input
                        type="checkbox"
                        name="is_featured"
                        id="is_featured"
                        value="1"
                        {{ old('is_featured') ? 'checked' : '' }}
                    >
                    <label for="is_featured">Featured Product</label>
                </div>

                <button type="submit">Save Product</button>
            </form>
        </div>
    </div>

</body>
</html>