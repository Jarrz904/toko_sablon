<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Mempercayai Proxy Vercel (Sangat Penting agar Login Tidak Error 500)
        $middleware->trustProxies(at: '*');

        // 2. Konfigurasi alias Anda sebelumnya (Tetap Dipertahankan)
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleManager::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();