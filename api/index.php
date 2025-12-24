<?php

// 1. Definisikan folder sementara yang bisa ditulisi di Vercel
$storagePath = '/tmp/storage';

// 2. Buat struktur folder di /tmp setiap kali ada request (jika belum ada)
$dirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. Load aplikasi
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Pengaturan krusial agar Laravel tidak mencari class [view] di tempat yang salah
$app->useStoragePath($storagePath);
$app->bind('path.public', function() { return __DIR__ . '/../public'; });

// Paksa config agar mengarah ke folder yang bisa ditulisi
config(['view.compiled' => $storagePath . '/framework/views']);
config(['cache.stores.file.path' => $storagePath . '/framework/cache']);
config(['session.files' => $storagePath . '/framework/sessions']);

// 5. Jalankan Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);