<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::latest()->get();
        return view('admin.service_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.service_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $folder = public_path('images/service_categories');

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folder, $filename);

            $validated['image'] = 'images/service_categories/' . $filename;
        }

        ServiceCategory::create($validated);

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Service main title created successfully.');
    }

    public function edit($id)
    {
        $category = ServiceCategory::findOrFail($id);
        return view('admin.service_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $folder = public_path('images/service_categories');

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folder, $filename);

            $validated['image'] = 'images/service_categories/' . $filename;
        }

        $category->update($validated);

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Service main title updated successfully.');
    }

    public function destroy($id)
    {
        $category = ServiceCategory::findOrFail($id);

        if ($category->services()->exists()) {
            return redirect()
                ->route('admin.service-categories.index')
                ->with('error', 'Cannot delete this main title because it has services under it.');
        }

        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->delete();

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Service main title deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Service main title status updated successfully.');
    }
}