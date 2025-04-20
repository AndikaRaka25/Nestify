<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider; // Anda mungkin perlu menyesuaikan ini jika tidak menggunakan RouteServiceProvider::HOME
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                return redirect()->route('filament.admin.pages.dashboard'); // Ganti '/admin/dashboard' jika perlu

                
            }
        }

        return $next($request);
    }
}