<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class GestionnaireMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'gestionnaire' ) {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have manager access');
    }
}
