<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Tentang Kami
Route::view('/about', 'user.about')->name('about');

// 3. Auth Group
Route::middleware('auth')->group(function () {
    
    // Dashboard Universal
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ROLE: USER ONLY ---
    Route::middleware(['verified', 'role:user'])->group(function () {
        
        // FITUR 1: PEMESANAN PAKET
        Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
        Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
        
        // FIX: TAMBAHAN ROUTE FINALIZE
        Route::post('/order/finalize', [OrderController::class, 'finalize'])->name('order.finalize');
        
        // FITUR 2: PEMESANAN SATUAN
        Route::get('/order/new', [UserController::class, 'create'])->name('orders.create'); 
        Route::post('/order/save', [UserController::class, 'store'])->name('orders.store'); 

        // STATUS, RIWAYAT & DETAIL
        Route::get('/status-pesanan', [OrderController::class, 'index'])->name('user.status');
        Route::get('/riwayat-pesanan', [OrderController::class, 'index'])->name('user.history');
        Route::get('/order-detail/{order}', [OrderController::class, 'show'])->name('order.show');

        // PEMBATALAN PESANAN
        Route::delete('/user/order/{id}', [OrderController::class, 'destroy'])->name('user.order.destroy');
        Route::delete('/order/cancel/{id}', [UserController::class, 'destroy'])->name('order.destroy'); 
    });

    // --- ROLE: ADMIN ONLY ---
    Route::middleware(['verified', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminOrderController::class, 'userIndex'])->name('admin.users');
        
        // Proses Admin (Store, Update, Delete)
        Route::post('/order/store', [AdminOrderController::class, 'store'])->name('admin.order.store');
        Route::patch('/order/{order}/{status}', [AdminOrderController::class, 'updateStatus'])->name('admin.order.update');
        Route::delete('/order/{order}', [AdminOrderController::class, 'destroy'])->name('admin.order.destroy');
    });

});

require __DIR__.'/auth.php';