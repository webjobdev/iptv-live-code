<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Guard;

class Updatedversion
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}