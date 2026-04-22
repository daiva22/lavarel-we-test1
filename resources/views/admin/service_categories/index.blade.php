<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Main Titles - AutoTech</title>
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
    <div class="topbar" style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Service Main Titles</h2>
        <a href="{{ route('admin.service-categories.create') }}" class="btn-reschedule">+ Add Main Title</a>
    </div>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="background:#f8d7da; color:#842029; padding:12px; margin-bottom:20px; border-radius:8px;">
            {{ session('error') }}
        </div>
    @endif

    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Main Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    @if($category->image)
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                    @else
                        —
                    @endif
                </td>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->is_active)
                        <span style="background:#d1e7dd; color:#0f5132; padding:6px 10px; border-radius:20px; font-size:13px;">Active</span>
                    @else
                        <span style="background:#e2e3e5; color:#41464b; padding:6px 10px; border-radius:20px; font-size:13px;">Inactive</span>
                    @endif
                </td>
                <td style="white-space:nowrap;">
                    <a href="{{ route('admin.service-categories.edit', $category->id) }}" class="btn-reschedule">Edit</a>

                    <form action="{{ route('admin.service-categories.toggleStatus', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-approve">
                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.service-categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this main title?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-reject">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No service main titles found.</td>
            </tr>
        @endforelse
    </table>
</div>
</body>
</html>