<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services - Admin</title>
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
    <a href="{{ route('home') }}">Back to Website</a>
</div>

<div class="main">
    <div class="topbar" style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Services</h2>
        <a href="{{ route('admin.services.create') }}" class="btn-reschedule">+ Add Service</a>
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
            <th>Service Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Mechanics</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        @forelse($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>
                    @if($service->image)
                        <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                    @else
                        —
                    @endif
                </td>
                <td>{{ $service->category?->name ?? '—' }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->description ? \Illuminate\Support\Str::limit($service->description, 60) : '—' }}</td>
                <td>Rs {{ number_format($service->price, 2) }}</td>
                <td>{{ $service->duration_minutes }} mins</td>
                <td>
                    @if($service->mechanics->count())
                        @foreach($service->mechanics as $mechanic)
                            <div>{{ $mechanic->name }}</div>
                        @endforeach
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if($service->is_active)
                        <span style="background:#d1e7dd; color:#0f5132; padding:6px 10px; border-radius:20px; font-size:13px;">Active</span>
                    @else
                        <span style="background:#e2e3e5; color:#41464b; padding:6px 10px; border-radius:20px; font-size:13px;">Inactive</span>
                    @endif
                </td>
                <td style="white-space:nowrap;">
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-reschedule">Edit</a>

                    <form action="{{ route('admin.services.toggleStatus', $service->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-approve">
                            {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this service?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-reject">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No services found.</td>
            </tr>
        @endforelse
    </table>
</div>

</body>
</html>