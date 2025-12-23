<?php
// Wajib ada: Membuat folder sementara agar Laravel bisa menulis cache/views
$storagePath = '/tmp/storage/framework';
if (!is_dir($storagePath . '/views')) {
    mkdir($storagePath . '/views', 0755, true);
    mkdir($storagePath . '/sessions', 0755, true);
    mkdir($storagePath . '/cache', 0755, true);
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Paksa Laravel menggunakan folder /tmp untuk compiled views
$app->useStoragePath('/tmp/storage');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);