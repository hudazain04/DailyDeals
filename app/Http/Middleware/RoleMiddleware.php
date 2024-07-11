<?php

namespace App\Http\Middleware;

use App\HttpResponse\HttpResponse;
use App\Types\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    use HttpResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $rolesArray = explode(',', $roles);

        if (Auth::check() && in_array(Auth::user()->role, $rolesArray))
        {
            return $next($request);
        }
        else{
            return $this->error('forbidden',403);
        }
    }
}
