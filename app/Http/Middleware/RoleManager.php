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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // CEK: Jika role user TIDAK SESUAI dengan syarat route
        if ($userRole !== $role) {
            // Jika dia admin tapi buka rute 'user', lempar ke admin dashboard
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            // Jika dia user tapi buka rute 'admin', lempar ke dashboard user
            if ($userRole === 'user') {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}