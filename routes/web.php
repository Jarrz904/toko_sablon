<?php
use App\Http\Controllers\AdminOrderController;
use Illuminate\Support\Facades\Route;

// 1. Rute Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// 2. Rute Auth (Login/Register - otomatis dari Breeze)
require __DIR__.'/auth.php';

// 3. Rute Khusus Admin (Harus Login & Role Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard Utama Admin
    Route::get('/admin/dashboard', [AdminOrderController::class, 'index'])
        ->name('admin.dashboard');

    // Simpan Pesanan Baru
    Route::post('/admin/order', [AdminOrderController::class, 'store'])
        ->name('admin.order.store');

    // Update Status Pesanan
    Route::patch('/admin/order/{order}/{status}', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.order.update');

    // Hapus Pesanan
    Route::delete('/admin/order/{id}', [AdminOrderController::class, 'destroy'])
        ->name('admin.order.destroy');
});