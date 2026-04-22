<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\ServiceFrontendController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ServiceReviewController;
use App\Http\Controllers\JsonSchemaController;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\ServiceCategory;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $serviceCategories = ServiceCategory::where('is_active', true)
        ->with(['services' => function ($query) {
            $query->where('is_active', true)->latest();
        }])
        ->latest()
        ->get();

    return view('home', compact('serviceCategories'));
})->name('home');

Route::get('/login', function () {
    return redirect()->route('account');
})->name('login');

Route::view('/account', 'account')->name('account');

/*
|--------------------------------------------------------------------------
| Register Route Fix
|--------------------------------------------------------------------------
| You are using account.blade.php for both login and register.
| So /register should also load the account page.
*/
Route::get('/register', function () {
    return view('account');
})->name('register');

Route::view('/cart', 'cart')->name('cart');

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

Route::post('/cart/add-ajax', [CartController::class, 'addAjax'])->name('cart.add.ajax');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::post('/product/{product}/review', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('product.review.store');

Route::post('/service/{service}/review', [ServiceReviewController::class, 'store'])
    ->middleware('auth')
    ->name('service.review.store');

Route::get('/booking', [FrontendBookingController::class, 'create'])->name('booking');
Route::get('/booking/availability', [FrontendBookingController::class, 'availability'])->name('booking.availability');
Route::post('/booking', [FrontendBookingController::class, 'store'])
    ->middleware('auth')
    ->name('booking.store');

Route::get('/services', [ServiceFrontendController::class, 'index'])->name('services.front.index');
Route::get('/services/category/{id}', [ServiceFrontendController::class, 'category'])->name('services.front.category');
Route::get('/service/{id}', [ServiceFrontendController::class, 'show'])->name('service.show');

Route::get('/json-schema/validate-creation', [JsonSchemaController::class, 'validateCreation'])->name('json.validate.creation');
Route::get('/json-schema/validate-consumption', [JsonSchemaController::class, 'validateConsumption'])->name('json.validate.consumption');

Route::get('/sound_system', function () {
    $subcategories = Subcategory::where('category', 'Sound System')->latest()->get();
    return view('sound_system', compact('subcategories'));
})->name('sound_system');

Route::get('/accessories', function () {
    $products = Product::where('category', 'Accessories')
        ->where('status', 'active')
        ->latest()
        ->get();

    return view('accessories', compact('products'));
})->name('accessories');

Route::get('/packages', function () {
    $products = Product::where('category', 'Packages')
        ->where('status', 'active')
        ->latest()
        ->get();

    return view('packages', compact('products'));
})->name('packages');

Route::get('/subcategory/{id}', function ($id) {
    $subcategory = Subcategory::findOrFail($id);

    $products = $subcategory->products()
        ->where('status', 'active')
        ->latest()
        ->get();

    return view('subcategory_products', compact('subcategory', 'products'));
})->name('subcategory.products');

Route::get('/product/{id}', [FrontendProductController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}/availability', [AdminBookingController::class, 'availability'])->name('bookings.availability');
    Route::post('/bookings/{id}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/{id}/reschedule', [AdminBookingController::class, 'reschedule'])->name('bookings.reschedule');
    Route::post('/bookings/closed-dates', [AdminBookingController::class, 'storeClosedDate'])->name('bookings.closed-dates.store');
    Route::delete('/bookings/closed-dates/{id}', [AdminBookingController::class, 'destroyClosedDate'])->name('bookings.closed-dates.destroy');

    Route::get('/service-categories', [ServiceCategoryController::class, 'index'])->name('service-categories.index');
    Route::get('/service-categories/create', [ServiceCategoryController::class, 'create'])->name('service-categories.create');
    Route::post('/service-categories', [ServiceCategoryController::class, 'store'])->name('service-categories.store');
    Route::get('/service-categories/{id}/edit', [ServiceCategoryController::class, 'edit'])->name('service-categories.edit');
    Route::put('/service-categories/{id}', [ServiceCategoryController::class, 'update'])->name('service-categories.update');
    Route::patch('/service-categories/{id}/toggle-status', [ServiceCategoryController::class, 'toggleStatus'])->name('service-categories.toggleStatus');
    Route::delete('/service-categories/{id}', [ServiceCategoryController::class, 'destroy'])->name('service-categories.destroy');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::patch('/services/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggleStatus');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index');
    Route::post('/subcategories', [SubcategoryController::class, 'store'])->name('subcategories.store');
    Route::delete('/subcategories/{subcategory}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});