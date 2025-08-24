<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController; // <-- Controller untuk request link reset
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;



// require_once base_path('routes/landing_page.php');

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']); // Route untuk proses login (POST) juga perlu

    // Registration Routes
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']); // Route untuk proses register (POST) juga perlu

});

Route::get('/', function () {
    return view('landing_page');
})->name('landing_page');



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        return match ($user->role) {
            'pemilik' => redirect('/admin'),
            'penyewa' => redirect('/user'),
            default   => redirect('/'), // Fallback jika peran tidak dikenali
        };
    })->name('dashboard');

Route::post('/notifications/{notification}/read', function (Request $request, DatabaseNotification $notification) {
    // pastikan notifikasi memang milik user yang login
    abort_unless($notification->notifiable_id === $request->user()->getAuthIdentifier(), 403);

    $notification->markAsRead();
    return back();
})->middleware('auth')->name('notifications.read');

});



