<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EnterpriseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\SellerAuthController;
use App\Http\Controllers\Seller\BusinessProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Seller Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/seller/register', [SellerAuthController::class, 'showRegistrationForm'])->name('seller.register');
    Route::post('/seller/register', [SellerAuthController::class, 'register']);
    Route::get('/seller/login', [SellerAuthController::class, 'showLoginForm'])->name('seller.login');
    Route::post('/seller/login', [SellerAuthController::class, 'login']);
});

Route::post('/seller/logout', [SellerAuthController::class, 'logout'])->name('seller.logout');

// Seller Portal Routes (Protected)
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', function () {
        // We will restrict to 'seller' role eventually, but standard auth is first barrier
        if(auth()->user()->role !== 'seller') abort(403, 'Unauthorized action.');
        return view('seller.dashboard.index');
    })->name('dashboard');

    // Business Profile Management
    Route::get('/profile', [BusinessProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [BusinessProfileController::class, 'update'])->name('profile.update');

    // Product Management
    Route::resource('products', App\Http\Controllers\Seller\ProductController::class);

    // Order Management
    Route::resource('orders', App\Http\Controllers\Seller\OrderController::class)->only(['index', 'show', 'update']);
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    Route::resource('users', UserController::class);

    Route::resource('enterprises', EnterpriseController::class);

    Route::resource('products', ProductController::class);

    Route::get('/inquiries', function () {
        return view('admin.inquiry-feedback.index');
    })->name('inquiries.index');

    Route::get('/content', function () {
        return view('admin.content-management.index');
    })->name('content.index');

    Route::get('/reports', function () {
        return view('admin.reports-logs.index');
    })->name('reports.index');
});
