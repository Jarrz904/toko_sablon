<?php

// 1. Definisikan jalur storage di folder /tmp (satu-satunya tempat yang bisa ditulisi)
$storagePath = '/tmp/storage';

// 2. Buat folder yang dibutuhkan secara paksa dengan izin 0755
$dirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        chmod($dir, 0755);
    }
}

// 3. Load Autoloader dan Inisialisasi Aplikasi
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Paksa Laravel menggunakan jalur baru ini (KRUSIAL)
$app->useStoragePath($storagePath);

// Konfigurasi tambahan agar engine 'view' tidak error
config(['view.compiled' => $storagePath . '/framework/views']);
config(['cache.stores.file.path' => $storagePath . '/framework/cache']);
config(['session.driver' => 'cookie']);

// 5. Jalankan Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);