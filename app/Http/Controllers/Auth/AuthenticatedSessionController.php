<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $this->validateLogin($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    public function handleAuthentication(Request $request)
    {
        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard()->user());
        }

        // Autentikasi berhasil
        $request->session()->regenerate();

        // Redirect ke halaman dashboard Filament setelah login
        return redirect()->route('filament::dashboard')->with('success', 'Login berhasil!');
    }

}

