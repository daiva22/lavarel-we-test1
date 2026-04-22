<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service Main Title - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="sidebar">
    <h2>AutoTech</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}">Products</a>
    <a href="{{ route('admin.subcategories.index') }}">Subcategories</a>
    <a href="{{ route('admin.service-categories.index') }}" class="active">Service Main Titles</a>
    <a href="{{ route('admin.services.index') }}">Services</a>
    <a href="{{ route('admin.bookings.index') }}">Bookings</a>
</div>

<div class="main">
    <div class="topbar">
        <h2>Add Service Main Title</h2>
    </div>

    @if($errors->any())
        <div style="background:#f8d7da; color:#842029; padding:12px; margin-bottom:20px; border-radius:8px;">
            <ul style="margin:0 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.service-categories.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="name">Main Title Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>

        <label for="image">Main Title Image</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">

        <label for="is_active">Status</label>
        <select id="is_active" name="is_active" required>
            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
        </select>

        <div style="margin-top:20px; display:flex; gap:10px;">
            <button type="submit" class="btn-approve">Save Main Title</button>
            <a href="{{ route('admin.service-categories.index') }}" class="btn-reject" style="text-decoration:none; display:inline-block;">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>