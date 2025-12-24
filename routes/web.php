<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

Route::get('/debug-final', function () {
    $results = [
        'database' => 'Mengecek...',
        'auth' => 'Mengecek...',
    ];

    try {
        DB::connection()->getPdo();
        $results['database'] = 'TERKONEKSI';
    } catch (\Exception $e) {
        $results['database'] = $e->getMessage();
    }

    $results['auth'] = Auth::check() ? 'LOGGED_IN' : 'NOT_LOGGED_IN';
    $results['session_driver'] = config('session.driver');

    return response()->json($results);
});