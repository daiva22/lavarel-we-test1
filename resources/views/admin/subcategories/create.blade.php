<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Subcategory</title>
</head>
<body>
    <h1>Create Subcategory</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Subcategory Name</label>
            <input type="text" name="name" required>
        </div>

        <div>
            <label>Category</label>
            <select name="category" required>
                <option value="Sound System">Sound System</option>
                <option value="Accessories">Accessories</option>
                <option value="Packages">Packages</option>
            </select>
        </div>

        <div>
            <label>Image</label>
            <input type="file" name="image">
        </div>

        <button type="submit">Save Subcategory</button>
    </form>
</body>
</html>