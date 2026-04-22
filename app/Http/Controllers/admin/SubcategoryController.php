<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    // Show all subcategories
    public function index()
    {
        $subcategories = Subcategory::latest()->get();

        return view('admin.subcategories.index', compact('subcategories'));
    }

    // Store new subcategory
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategories', 'public');
        }

        $subcategory = Subcategory::create([
            'name' => $request->name,
            'category' => $request->category,
            'image' => $imagePath,
        ]);

        // 🔥 IMPORTANT: redirect to add product with subcategory selected
        return redirect()->route('admin.products.create', [
            'subcategory_id' => $subcategory->id
        ])->with('success', 'Subcategory created. Now add products.');
    }

    // Delete subcategory (ONLY if empty)
    public function destroy(Subcategory $subcategory)
    {
        if ($subcategory->products()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete. Subcategory still has products.');
        }

        $subcategory->delete();

        return redirect()->back()->with('success', 'Subcategory deleted successfully.');
    }
}