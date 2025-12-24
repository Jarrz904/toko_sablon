<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

Route::get('/debug-final', function () {
    $results = [];

    // 1. Cek Koneksi Database & SSL
    try {
        $pdo = DB::connection()->getPdo();
        $sslStatus = DB::select("SHOW STATUS LIKE 'Ssl_cipher'");
        $results['database'] = [
            'status' => 'TERKONEKSI',
            'database_name' => DB::connection()->getDatabaseName(),
            'ssl_used' => !empty($sslStatus) ? $sslStatus[0]->Value : 'TIDAK PAKAI SSL (Bahaya!)',
        ];
    } catch (\Exception $e) {
        $results['database'] = ['status' => 'GAGAL', 'error' => $e->getMessage()];
    }

    // 2. Cek Data User yang Sedang Login
    if (Auth::check()) {
        $results['auth'] = [
            'status' => 'LOGGED_IN',
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role, // Ini yang bikin Dashboard error kalau kosong
        ];
    } else {
        $results['auth'] = ['status' => 'NOT_LOGGED_IN', 'pesan' => 'Anda belum login atau session hilang'];
    }

    // 3. Cek Konfigurasi Serverless
    $results['server_env'] = [
        'session_driver' => config('session.driver'),
        'app_key_exists' => !empty(config('app.key')),
    ];

    return response()->json($results);
});