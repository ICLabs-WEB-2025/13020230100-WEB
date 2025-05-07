<?php
namespace App\Http\Controllers; 

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Route untuk auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/reports', [ReportController::class, 'index'])->name('reports');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


// Resource Routes (dengan middleware auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('home');
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('reports', ReportController::class);
    Route::redirect('/', '/dashboard');
    
    // Tambahan route untuk layanan jika diperlukan
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
});