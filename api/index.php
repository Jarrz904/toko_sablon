<?php

// 1. Tentukan folder storage sementara
$storagePath = '/tmp/storage';

// 2. Buat folder yang diperlukan
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/bootstrap/cache'] as $path) {
    if (!is_dir($storagePath . $path)) {
        mkdir($storagePath . $path, 0755, true);
    }
}

// 3. Muat aplikasi
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Paksa Laravel menggunakan /tmp
$app->useStoragePath($storagePath);

// 5. Jalankan Kernel (Tanpa memanggil helper config() di sini)
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);