<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; 
use Illuminate\Auth\AuthenticationException; 
use Illuminate\Http\Request;  

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // <-- Cari bagian ini

        // Daftarkan alias middleware di sini
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class, // Updated to use the correct namespace
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // <-- PASTIKAN BARIS INI ADA DAN BENAR
            // ... alias lainnya mungkin ada di sini
        ]);

        // Mungkin ada konfigurasi middleware lain di sini (global, group, dll.)

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

           
            return redirect()->guest('/');
        });
    })->create();