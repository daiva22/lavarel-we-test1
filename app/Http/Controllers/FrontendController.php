<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;

class FrontendController extends Controller
{
    // Show subcategories for one category name
    public function showCategory($categoryName)
    {
        $subcategories = Subcategory::where('category', $categoryName)->latest()->get();

        return view('frontend.category-show', compact('subcategories', 'categoryName'));
    }

    // Show products for one subcategory
    public function showSubcategory($id)
    {
        $subcategory = Subcategory::with('products')->findOrFail($id);

        return view('frontend.subcategory-show', compact('subcategory'));
    }
}