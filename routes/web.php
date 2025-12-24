<?php
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\DashboardController;

// Rute untuk User Biasa
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:user'])
    ->name('dashboard');

// Rute untuk Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Halaman Utama Admin
    Route::get('/admin/dashboard', [AdminOrderController::class, 'index'])
        ->name('admin.dashboard');

    // Simpan Pesanan (Manual)
    Route::post('/admin/order/store', [AdminOrderController::class, 'store'])
        ->name('admin.order.store');

    // Update Status (Sesuai dengan view Anda)
    Route::patch('/admin/order/update/{order}/{status}', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.order.update');

    // Hapus Pesanan
    Route::delete('/admin/order/destroy/{id}', [AdminOrderController::class, 'destroy'])
        ->name('admin.order.destroy');
});