<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPaid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->has_paid) {
            return $next($request);
        }
        return redirect('/checkout'); 
    }
}
