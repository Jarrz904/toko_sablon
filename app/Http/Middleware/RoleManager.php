<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // 2. Jika role sesuai, langsung izinkan lewat
        if ($userRole === $role) {
            return $next($request);
        }

        // 3. Jika TIDAK sesuai, arahkan ke halaman utama masing-masing role
        // Ini mencegah redirect loop jika user mencoba akses rute yang salah
        return match($userRole) {
            'admin' => redirect()->route('admin.dashboard'),
            'vendor' => redirect()->route('vendor.dashboard'), // sesuaikan jika ada role vendor
            default => redirect()->route('dashboard'),
        };
    }
}