<?php

namespace App\Http\Responses;
use Illuminate\Support\Facades\Auth;
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
         $role = Auth::user()->role;

        // Siapkan variabel untuk menyimpan tujuan redirect.
        $redirectUrl = '';

        // Terapkan logika pengalihan berdasarkan peran.
        switch ($role) {
            case 'pemilik':
                // Jika peran adalah 'pemilik', arahkan ke panel admin.
                $redirectUrl = '/admin';
                break;
            case 'penyewa':
                // Jika peran adalah 'penyewa', arahkan ke panel user.
                $redirectUrl = '/user';
                break;
            default:
                // Sebagai fallback, jika peran tidak dikenali, arahkan ke halaman utama.
                $redirectUrl = '/';
                break;
        }

        // Lakukan redirect ke URL yang telah ditentukan.
        return new RedirectResponse($redirectUrl);
    }
}