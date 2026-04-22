<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with([
                'subcategory',
                'reviews.user',
            ])
            ->where('status', 'active')
            ->findOrFail($id);

        $suggestedServices = Service::with('category')
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.product_show', compact('product', 'suggestedServices'));
    }
}