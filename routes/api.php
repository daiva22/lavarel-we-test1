<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service;

Route::get('/services', function () {
    return Service::where('is_active', true)->get();
});