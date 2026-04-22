<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // ✅ Show all products WITH pagination
    public function index()
    {
        $products = Product::with('subcategory')
            ->latest()
            ->paginate(10); // pagination added

        return view('admin.products.index', compact('products'));
    }

    // Show add product form
    public function create(Request $request)
    {
        $subcategories = Subcategory::latest()->get();
        $selectedSubcategoryId = $request->subcategory_id;

        return view('admin.products.create', compact('subcategories', 'selectedSubcategoryId'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            $destination = public_path('images/products');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);

            $imagePath = 'products/' . $filename;
        }

        $subcategory = Subcategory::findOrFail($request->subcategory_id);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category' => $subcategory->category,
            'subcategory_id' => $request->subcategory_id,
            'stock' => $request->stock,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product added successfully.');
    }

    // Show edit product form
    public function edit(Product $product)
    {
        $subcategories = Subcategory::latest()->get();

        return view('admin.products.edit', compact('product', 'subcategories'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            $destination = public_path('images/products');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            // delete old image
            if ($product->image && File::exists(public_path('images/' . $product->image))) {
                File::delete(public_path('images/' . $product->image));
            }

            $file->move($destination, $filename);

            $imagePath = 'products/' . $filename;
        }

        $subcategory = Subcategory::findOrFail($request->subcategory_id);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category' => $subcategory->category,
            'subcategory_id' => $request->subcategory_id,
            'stock' => $request->stock,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path('images/' . $product->image))) {
            File::delete(public_path('images/' . $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}