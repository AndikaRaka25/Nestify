<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Filament\Auth\MyLogoutResponse; 
use Filament\Support\Facades\FilamentAsset;
use App\Http\Controllers\Auth\LogoutController;
use Filament\Http\Responses\Auth\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
    }
}
