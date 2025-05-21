<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;

// Route untuk auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route dengan middleware auth
Route::middleware(['auth'])->group(function () {
    // Redirect root ke dashboard sesuai role
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // Resource Routes
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('services', ServiceController::class); // Ini untuk menu Services

    // Update status pesanan
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{id}/update-status', [OrderController::class, 'updateStatusForm'])->name('orders.updateStatusForm');

    // User-specific routes
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/orders', [OrderController::class, 'userOrders'])->name('user.orders');
    Route::get('/user/settings', [UserController::class, 'settings'])->name('user.settings');
});

// Route untuk dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // User dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
});

use App\Services\WhatsappHelper;

Route::get('/test-whatsapp', function () {
    $whatsapp = new \App\Services\WhatsappHelper();
    $response = $whatsapp->sendMessage('+6282297233332', 'Tes kirim dari Laravel untuk Pelanggan', [
        'countryCode' => '62',
        'typing' => true,
    ]);
    return response()->json($response);
});

Route::get('/test-token', function () {
    return response()->json(['token' => config('services.fonnte.api_key')]);
});

Route::get('/test-fonnte', function() {
    return response()->json([
        'api_key' => config('services.fonnte.api_key'),
        'status' => 'Konfigurasi Fonnte',
        'loaded' => !empty(config('services.fonnte.api_key'))
    ]);
});

Route::get('/check-env', function() {
    return response()->json([
        'env_key' => env('FONNTE_API_KEY'),
        'config_key' => config('services.fonnte.api_key')
    ]);
});