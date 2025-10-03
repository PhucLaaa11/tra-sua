<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ (ai cũng vào được)
Route::get('/', [ProductController::class, 'homepage'])->name('homepage');

// Dashboard (sau khi login sẽ chuyển về homepage luôn)
Route::get('/dashboard', function () {
    return redirect()->route('homepage');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authentication Routes (thay cho routes/auth.php của Breeze)
|--------------------------------------------------------------------------
*/
// Đăng ký
Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Đăng nhập
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

// Đăng xuất
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Quên mật khẩu - form nhập email
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

// Gửi email reset
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Form đặt lại mật khẩu
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

// Lưu mật khẩu mới
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

/*
|--------------------------------------------------------------------------
| Routes dành cho user đã login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Quản lý sản phẩm
    Route::resource('products', ProductController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Đổi mật khẩu (nếu bạn có controller PasswordController)
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
