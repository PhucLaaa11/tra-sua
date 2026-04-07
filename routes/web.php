<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
// ADD NEW CONTROLLERS
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'homepage'])->name('homepage');

// Public Menu Page
Route::get('/menu', [ProductController::class, 'menu'])->name('menu.index');

// Public Product Detail Page
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->where('id', '[0-9]+') // Force ID to be numeric
    ->name('products.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
// Registration
Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Login / Logout
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Password Reset
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Authenticated User Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return view('dashboard', ['user' => $user]);
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | ADMIN & STAFF Group
    |--------------------------------------------------------------------------
    | Routes accessible by both Admin and Staff
    */
    Route::middleware(['auth', 'role:admin,staff'])->group(function () {
        // Order Management (Staff role required)
        Route::get('/admin/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::put('/admin/orders/{id}', [App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('admin.orders.update');

        // Product List & Quick Update
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::put('/products/{id}/quick', [ProductController::class, 'quickUpdate'])->name('products.quick_update');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY Group
    |--------------------------------------------------------------------------
    | Routes restricted to Admin role
    */
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Full Product Management (CRUD)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Category Management
        Route::resource('admin/categories', App\Http\Controllers\CategoryController::class)
            ->names('admin.categories');

        // User Management
        Route::get('/admin/users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::put('/admin/users/{id}/role', [App\Http\Controllers\AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');

        // Revenue Dashboard
        Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | STAFF Role
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/orders', function () {
            return view('orders.index'); 
        })->name('orders.index');
    });

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER Role
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:customer'])->group(function () {

        // Order History
        Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');

        // CART ROUTES
        Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // CHECKOUT ROUTES
        Route::get('/checkout', [OrderController::class, 'showCheckoutForm'])->name('checkout.show');
        Route::post('/order', [OrderController::class, 'storeOrder'])->name('order.store');
    });

    // Product Review Submission
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});