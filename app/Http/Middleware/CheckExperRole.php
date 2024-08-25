<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

use Symfony\Component\HttpFoundation\Response;

class CheckExperRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user has either the "expert" or "admin" role
        if ($request->user() && ($request->user()->role == 'expert' || $request->user()->role == 'supervisor'|| $request->user()->role == 'expert-supervisor')) {
            return $next($request);
        }
        
      
        throw new AuthenticationException('حساب کاربری شما هنوز فعال نشده است. لطفاً با پشتیبانی تماس بگیرید.');
    }
}
