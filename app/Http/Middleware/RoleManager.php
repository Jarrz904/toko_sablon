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

        // Jika role tidak sesuai, arahkan ke dashboard masing-masing HANYA jika berbeda rute
        if ($userRole !== $role) {
            if ($userRole === 'admin' && $request->routeIs('dashboard')) {
                return redirect()->route('admin.dashboard');
            }
            if ($userRole === 'user' && $request->is('admin/*')) {
                return redirect()->route('dashboard');
            }
            
            // Jika akses benar-benar terlarang
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}