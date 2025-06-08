<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

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
    $user = Auth::guard()->user(); // Dapatkan user yang baru login

    $redirectRoute = '/'; // Default fallback route

    if ($user instanceof User && method_exists($user, 'hasRole')) {
        if ($user->hasRole('admin')) {
            $redirectRoute = 'filament.admin.dashboard'; // Route ke admin dashboard
        } elseif ($user->hasRole('user')) {
            $redirectRoute = 'filament.user.dashboard'; // Route ke user dashboard
        }
    }

    $request->session()->regenerate(); // Regenerate session setelah login

    // Arahkan ke route yang sesuai
    return redirect()->route($redirectRoute)->with('success', 'Login berhasil!');
}
    
        public function destroy(Request $request)
        {
            Auth::guard()->logout();
    
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect('/')->with('success', 'Logout berhasil!');
        }

}

