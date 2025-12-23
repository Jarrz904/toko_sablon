<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next) {
    if (auth()->check() && auth()->user()->is_admin) {
        return $next($request);
    }
    return redirect('/dashboard')->with('error', 'Anda tidak punya akses admin.');
}
}
