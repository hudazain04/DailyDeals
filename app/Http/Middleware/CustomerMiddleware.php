<?php

namespace App\Http\Middleware;

use App\HttpResponse\HttpResponse;
use App\Types\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    use HttpResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === UserType::Customer)
        {
            return $next($request);
        }
        else{
            return $this->error('forbidden',403);
        }    }
}
