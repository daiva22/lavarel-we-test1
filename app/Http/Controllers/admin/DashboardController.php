<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalProducts = Product::count();
        $totalServices = Service::count();

        $recentBookings = Booking::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalProducts',
            'totalServices',
            'recentBookings'
        ));
    }
}