<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPaid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->has_paid) {
            return redirect('/'); 
        }

        return $next($request);
    }
}
