<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Closure;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        // tambahkan field yang jangan di-trim, mis. 'password'
    ];

    /**
     * Handle an incoming request.
     *
     * Menjadikan operasi trim defensif: hanya memproses nilai string.
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = trim($value);
            }
        });

        $request->replace($input);

        return $next($request);
    }
}