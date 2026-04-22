<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceFrontendController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->with(['services' => function ($query) {
                $query->where('is_active', true)->latest();
            }])
            ->latest()
            ->get();

        return view('frontend.services.index', compact('categories'));
    }

    public function category($id)
    {
        $category = ServiceCategory::where('is_active', true)
            ->with(['services' => function ($query) {
                $query->where('is_active', true)->latest();
            }])
            ->findOrFail($id);

        return view('frontend.services.category', compact('category'));
    }

    public function show($id)
    {
        $service = Service::with([
                'category',
                'reviews.user',
            ])
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->findOrFail($id);

        return view('frontend.services.show', compact('service'));
    }
}