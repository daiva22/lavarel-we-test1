<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service - AutoTech</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="sidebar">
    <h2>AutoTech</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}">Products</a>
    <a href="{{ route('admin.subcategories.index') }}">Subcategories</a>
    <a href="{{ route('admin.service-categories.index') }}">Service Main Titles</a>
    <a href="{{ route('admin.services.index') }}" class="active">Services</a>
    <a href="{{ route('admin.bookings.index') }}">Bookings</a>
</div>

<div class="main">
    <div class="topbar">
        <h2>Add Service</h2>
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

    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="service_category_id">Main Title</label>
        <select id="service_category_id" name="service_category_id" required>
            <option value="">Select Main Title</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label for="name">Service Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>

        <label for="image">Service Image</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">

        <label for="price">Price</label>
        <input type="number" id="price" step="0.01" min="0" name="price" value="{{ old('price') }}" required>

        <label for="duration_minutes">Duration (minutes)</label>
        <input type="number" id="duration_minutes" min="1" name="duration_minutes" value="{{ old('duration_minutes') }}" required>

        <label for="mechanics">Assign Mechanics</label>
        <select id="mechanics" name="mechanics[]" multiple style="min-height: 140px;">
            @foreach($mechanics as $mechanic)
                <option value="{{ $mechanic->id }}" {{ collect(old('mechanics'))->contains($mechanic->id) ? 'selected' : '' }}>
                    {{ $mechanic->name }}{{ $mechanic->specialty ? ' - ' . $mechanic->specialty : '' }}
                </option>
            @endforeach
        </select>
        <small style="display:block; margin-top:8px; color:#666;">Hold Command or Ctrl to select multiple mechanics.</small>

        <label for="is_active">Status</label>
        <select id="is_active" name="is_active" required>
            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
        </select>

        <div style="margin-top:20px; display:flex; gap:10px;">
            <button type="submit" class="btn-approve">Save Service</button>
            <a href="{{ route('admin.services.index') }}" class="btn-reject" style="text-decoration:none; display:inline-block;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>