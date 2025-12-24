<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * Atribut yang tidak boleh di-trim.
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}