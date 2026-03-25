<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/discover', [LandingPageController::class, 'discover'])->name('discover');

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EnterpriseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\SellerAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Seller\BusinessProfileController;
use App\Http\Controllers\Seller\FeedbackController;
use App\Http\Controllers\PublicStoreController;
use App\Http\Controllers\PublicProductController;

/*
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/store/{id}', [PublicStoreController::class, 'show'])->name('store.show');
Route::get('/product/{id}', [PublicProductController::class, 'show'])->name('product.show');

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CustomerOrderController;

// Cart & Review Routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout Routes
    Route::get('/checkout', [\App\Http\Controllers\Customer\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\Customer\CheckoutController::class, 'store'])->name('checkout.store');

    // My Purchases
    Route::get('/purchases', [CustomerOrderController::class, 'index'])->name('purchases.index');

    // Customer Inquiries (chatbox AJAX)
    Route::post('/inquiry/{enterpriseId}/start', [\App\Http\Controllers\Customer\InquiryController::class, 'startOrGet'])->name('inquiry.start');
    Route::post('/inquiry/{conversationId}/send', [\App\Http\Controllers\Customer\InquiryController::class, 'send'])->name('inquiry.send');
    Route::post('/inquiry/{conversationId}/auto-reply', [\App\Http\Controllers\Customer\InquiryController::class, 'autoReply'])->name('inquiry.auto-reply');
    Route::get('/inquiry/{conversationId}/poll', [\App\Http\Controllers\Customer\InquiryController::class, 'poll'])->name('inquiry.poll');

    // Product Reviews
    Route::post('/product/{product}/review', [\App\Http\Controllers\PublicReviewController::class, 'store'])->name('review.store');
    Route::put('/product/review/{review}', [\App\Http\Controllers\PublicReviewController::class, 'update'])->name('review.update');
    Route::post('/product/review/{review}/report', [\App\Http\Controllers\PublicReviewController::class, 'report'])->name('review.report');
});

// Standard Customer Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('/logout', [CustomerAuthController::class, 'logout']); // Fallback to prevent 419 on manual links

// Seller Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/seller/register', [SellerAuthController::class, 'showRegistrationForm'])->name('seller.register');
    Route::post('/seller/register', [SellerAuthController::class, 'register']);
    Route::get('/seller/login', [SellerAuthController::class, 'showLoginForm'])->name('seller.login');
    Route::post('/seller/login', [SellerAuthController::class, 'login']);
});

Route::post('/seller/logout', [SellerAuthController::class, 'logout'])->name('seller.logout');
Route::get('/seller/logout', [SellerAuthController::class, 'logout']); // Fallback to prevent 419 on manual links
Route::middleware('auth')->post('/seller/upgrade', [SellerAuthController::class, 'upgradeToSeller'])->name('seller.upgrade');

// Seller Portal Routes (Protected)
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');

    // Business Profile Management
    Route::get('/profile', [BusinessProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [BusinessProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [BusinessProfileController::class, 'update'])->name('profile.update');

    // Product Management
    Route::resource('products', App\Http\Controllers\Seller\ProductController::class);

    // Order Management
    Route::resource('orders', App\Http\Controllers\Seller\OrderController::class)->only(['index', 'show', 'update']);

    // Feedback & Ratings
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback/{review}/reply', [FeedbackController::class, 'reply'])->name('feedback.reply');
    Route::post('/feedback/{review}/report', [FeedbackController::class, 'report'])->name('feedback.report');

    // Inquiries
    Route::get('/inquiries', [\App\Http\Controllers\Seller\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{id}/messages', [\App\Http\Controllers\Seller\InquiryController::class, 'messages'])->name('inquiries.messages');
    Route::post('/inquiries/{id}/reply', [\App\Http\Controllers\Seller\InquiryController::class, 'reply'])->name('inquiries.reply');
    Route::patch('/inquiries/{id}/close', [\App\Http\Controllers\Seller\InquiryController::class, 'close'])->name('inquiries.close');

    // Quick Replies
    Route::get('/quick-replies', [\App\Http\Controllers\Seller\QuickReplyController::class, 'index'])->name('quick-replies.index');
    Route::post('/quick-replies', [\App\Http\Controllers\Seller\QuickReplyController::class, 'store'])->name('quick-replies.store');
    Route::put('/quick-replies/{id}', [\App\Http\Controllers\Seller\QuickReplyController::class, 'update'])->name('quick-replies.update');
    Route::delete('/quick-replies/{id}', [\App\Http\Controllers\Seller\QuickReplyController::class, 'destroy'])->name('quick-replies.destroy');
    Route::post('/quick-replies/reorder', [\App\Http\Controllers\Seller\QuickReplyController::class, 'reorder'])->name('quick-replies.reorder');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);

    Route::resource('enterprises', EnterpriseController::class);

    Route::resource('products', ProductController::class);

    Route::get('/inquiries', [\App\Http\Controllers\Admin\AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('/inquiries/{id}/close', [\App\Http\Controllers\Admin\AdminInquiryController::class, 'close'])->name('inquiries.close');
    Route::patch('/inquiries/{id}/reopen', [\App\Http\Controllers\Admin\AdminInquiryController::class, 'reopen'])->name('inquiries.reopen');
    Route::delete('/inquiries/messages/{id}', [\App\Http\Controllers\Admin\AdminInquiryController::class, 'deleteMessage'])->name('inquiries.message.delete');

    Route::prefix('content')->name('content.')->group(function () {
        // Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::post('categories/reorder', [\App\Http\Controllers\Admin\CategoryController::class, 'reorder'])->name('categories.reorder');
        
        // Featured Products
        Route::get('featured', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'index'])->name('featured.index');
        Route::post('featured/{product}', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'store'])->name('featured.store');
        Route::delete('featured/{product}', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'destroy'])->name('featured.destroy');
        Route::post('featured/reorder', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'reorder'])->name('featured.reorder');

        // Product Discovery Settings
        Route::get('discovery', [\App\Http\Controllers\Admin\AdminContentController::class, 'discoverySettings'])->name('discovery');
        Route::put('discovery', [\App\Http\Controllers\Admin\AdminContentController::class, 'updateDiscoverySettings'])->name('discovery.update');

        // Product Moderation
        Route::get('moderation', [\App\Http\Controllers\Admin\ProductModerationController::class, 'index'])->name('moderation.index');
        Route::patch('moderation/{product}', [\App\Http\Controllers\Admin\ProductModerationController::class, 'update'])->name('moderation.update');

        // Homepage Content
        Route::get('/', [\App\Http\Controllers\Admin\AdminContentController::class, 'index'])->name('index');
        Route::put('/', [\App\Http\Controllers\Admin\AdminContentController::class, 'update'])->name('update');
        Route::delete('banner', [\App\Http\Controllers\Admin\AdminContentController::class, 'removeBanner'])->name('banner.destroy');
    });

    Route::get('/reports', function () {
        return view('admin.reports-logs.index');
    })->name('reports.index');
});
