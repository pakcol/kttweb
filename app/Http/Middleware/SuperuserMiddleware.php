<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperuserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->roles === 'superuser') {
            return $next($request);
        }

        return redirect('/homeDb')->with('error', 'Akses ditolak. Hanya superuser yang dapat mengakses halaman ini.');
    }
}