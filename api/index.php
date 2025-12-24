<?php

// 1. Definisikan folder sementara (Satu-satunya tempat yang bisa ditulisi di Vercel)
$storagePath = '/tmp/storage';

// 2. Buat folder yang diperlukan jika belum ada
$directories = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/bootstrap/cache',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

// 3. Load Autoloader dan Inisialisasi Aplikasi
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Atur Storage Path secara internal (Tanpa memanggil helper config)
$app->useStoragePath($storagePath);

// 5. Jalankan Kernel Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);