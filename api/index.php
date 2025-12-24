<?php

// 1. Definisikan folder sementara untuk penulisan file
$storagePath = '/tmp/storage';

// 2. Buat struktur folder secara paksa di memori Vercel
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

// 3. Muat autoloader dan inisialisasi aplikasi
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Paksa Laravel menggunakan /tmp untuk semua aktivitas penulisan
$app->useStoragePath($storagePath);

// Tambahkan binding ini untuk memperbaiki error [view]
$app->register(\Illuminate\View\ViewServiceProvider::class);

// Set jalur compile blade secara manual
config(['view.compiled' => $storagePath . '/framework/views']);

// 5. Jalankan Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);