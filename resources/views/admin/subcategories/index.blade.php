<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subcategories - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <div class="sidebar">
        <h2>AutoTech</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.products.index') }}">Products</a>
        <a href="{{ route('admin.subcategories.index') }}" class="active">Subcategories</a>
        <a href="{{ route('admin.services.index') }}">Services</a>
        <a href="{{ route('admin.bookings.index') }}">Bookings</a>
        <a href="{{ route('home') }}">Back to Website</a>
    </div>

    <div class="main">
        <div class="topbar">Manage Subcategories</div>

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
            <h3>Add New Subcategory</h3>

            <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="category">Category Name</label>
                <input
                    type="text"
                    name="category"
                    id="category"
                    placeholder="Example: Sound System"
                    value="{{ old('category') }}"
                    required
                >

                <label for="name">Subcategory Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    placeholder="Example: Speakers"
                    value="{{ old('name') }}"
                    required
                >

                <label for="image">Subcategory Image</label>
                <input type="file" name="image" id="image">

                <button type="submit">Create Subcategory</button>
            </form>
        </div>

        <div class="card">
            <h3>All Subcategories</h3>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Products Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcategories as $subcategory)
                        <tr>
                            <td>{{ $subcategory->id }}</td>

                            <td>
                                @if($subcategory->image)
                                    <img
                                        src="{{ asset('storage/' . $subcategory->image) }}"
                                        alt="{{ $subcategory->name }}"
                                        class="thumb"
                                    >
                                @else
                                    <span class="empty">No image</span>
                                @endif
                            </td>

                            <td>{{ $subcategory->category }}</td>
                            <td>{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->products->count() }}</td>
                            <td>
                                <form action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">No subcategories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>