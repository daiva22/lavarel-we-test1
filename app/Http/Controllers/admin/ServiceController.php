<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['category', 'mechanics'])->latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)->latest()->get();
        $mechanics = Mechanic::where('is_active', true)->latest()->get();

        return view('admin.services.create', compact('categories', 'mechanics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'mechanics' => 'nullable|array',
            'mechanics.*' => 'exists:mechanics,id',
        ]);

        if ($request->hasFile('image')) {
            $folder = public_path('images/services');

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folder, $filename);

            $validated['image'] = 'images/services/' . $filename;
        }

        unset($validated['mechanics']);

        $service = Service::create($validated);

        $service->mechanics()->sync($request->mechanics ?? []);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit($id)
    {
        $service = Service::with('mechanics')->findOrFail($id);
        $categories = ServiceCategory::latest()->get();
        $mechanics = Mechanic::where('is_active', true)->latest()->get();

        return view('admin.services.edit', compact('service', 'categories', 'mechanics'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'mechanics' => 'nullable|array',
            'mechanics.*' => 'exists:mechanics,id',
        ]);

        if ($request->hasFile('image')) {
            $folder = public_path('images/services');

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            if ($service->image && File::exists(public_path($service->image))) {
                File::delete(public_path($service->image));
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folder, $filename);

            $validated['image'] = 'images/services/' . $filename;
        }

        unset($validated['mechanics']);

        $service->update($validated);

        $service->mechanics()->sync($request->mechanics ?? []);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        if ($service->bookings()->exists()) {
            return redirect()
                ->route('admin.services.index')
                ->with('error', 'This service cannot be deleted because it already has bookings.');
        }

        if ($service->image && File::exists(public_path($service->image))) {
            File::delete(public_path($service->image));
        }

        $service->mechanics()->detach();
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service status updated successfully.');
    }
}