<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // Jika pengguna adalah pemilik kos, arahkan ke dashboard admin
        if ($user->hasRole('pemilik_kos')) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        // Jika pengguna adalah penghuni kos, arahkan ke dashboard user
        if ($user->hasRole('penghuni_kos')) {
            return redirect()->route('filament.user.pages.dashboard');
        }

        // Pengarahan default jika tidak memiliki peran di atas
        return redirect(config('fortify.home'));
    }
}