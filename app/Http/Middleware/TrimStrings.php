<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Closure;
use Illuminate\Support\Arr;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
        // tambahkan field yang tidak ingin di-trim
    ];

    /**
     * Handle an incoming request — hanya trim nilai yang bertipe string.
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = trim($value);
            }
        });

        // Merge lebih aman agar file/file upload tetap berada di $request->files
        $request->merge($input);

        return $next($request);
    }
}