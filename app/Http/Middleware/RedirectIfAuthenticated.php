<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider; // Anda mungkin perlu menyesuaikan ini jika tidak menggunakan RouteServiceProvider::HOME
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User; // Pastikan Anda mengimpor model User jika diperlukan


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
                /** @var User $user */ // Type hint untuk auto-completion
                $user = Auth::guard($guard)->user();

                // Pastikan $user adalah instance dari User dan memiliki method hasRole
                if ($user instanceof User && method_exists($user, 'hasRole')) {
                    if ($user->hasRole('admin')) {
                        // Arahkan admin ke panel admin menggunakan nama route
                        return redirect()->route('filament.admin.dashboard'); // Ganti 'admin' sesuai ID panel Anda
                    } elseif ($user->hasRole('user')) {
                         // Arahkan user ke panel user menggunakan nama route
                        return redirect()->route('filament.user.dashboard'); // Ganti 'user' sesuai ID panel Anda
                    }
                }

                // Fallback redirect jika user tidak punya role atau $user bukan instance yang diharapkan
                return redirect('/');
            }
        }

        return $next($request);
    }
}