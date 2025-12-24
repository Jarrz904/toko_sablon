<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Models\Order;
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
        
        /**
         * FITUR: PEMESANAN (Pusat Kendali di OrderController)
         * Sinkronisasi rute agar tidak error di welcome.blade.php dan dashboard.blade.php
         */
        // Rute Utama Form Pemesanan
        Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
        
        // ALIAS: Agar link di welcome.blade.php (baris 386) tidak error
        Route::get('/order/make', [OrderController::class, 'create'])->name('orders.create');
        
        // Rute Simpan Pesanan (DENGAN ALIAS UNTUK DASHBOARD)
        Route::post('/order/store', [OrderController::class, 'store'])->name('orders.store');
        
        // FIX: Tambahkan alias 'order.store' tanpa huruf 's' agar form di dashboard.blade.php (baris 69) tidak error
        Route::post('/order/save-request', [OrderController::class, 'store'])->name('order.store');
        
        // Route Finalize (Wajib untuk Midtrans Snap)
        Route::post('/order/finalize', [OrderController::class, 'finalize'])->name('order.finalize');
        
        /**
         * FITUR BACKUP: PEMESANAN SATUAN (UserController)
         * Nama rute dibedakan agar tidak konflik dengan OrderController
         */
        Route::get('/order/new', [UserController::class, 'create'])->name('orders.new'); 
        Route::post('/order/save', [UserController::class, 'store'])->name('orders.save'); 

        /**
         * STATUS, RIWAYAT & DETAIL
         */
        // Menampilkan pesanan aktif
        Route::get('/status-pesanan', [OrderController::class, 'index'])->name('user.status');
        
        // Menampilkan riwayat pesanan
        Route::get('/riwayat-pesanan', [OrderController::class, 'index'])->name('user.history');
        
        // Route Detail Pesanan
        Route::get('/order-detail/{order}', [OrderController::class, 'indexDetailed'])->name('order.show');

        /**
         * PEMBATALAN PESANAN
         */
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