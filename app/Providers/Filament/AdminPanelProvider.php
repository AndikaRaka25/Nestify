<?php

namespace App\Providers\Filament;


use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Pages\Settings;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Http\Responses\LogoutResponse;
use App\Filament\Widgets\KamarOverview;
use App\Filament\Widgets\StatsOverview;
use Filament\Navigation\NavigationGroup;
use App\Filament\Widgets\PropertiOverview;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use App\Models\User;

class AdminPanelProvider extends PanelProvider
{

    public function register(): void
    {
        parent::register(); 

        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    public function panel(Panel $panel): Panel
    {
        
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                
            ])
            ->widgets([
                StatsOverview::class,
                PropertiOverview::class,
                
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn (): string => Settings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->brandName('Nestify') 
            ->brandLogo(asset('storage/landing_page/logo_nestify.png')) 
            ->brandLogoHeight('2rem') 
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->spa()
            ->sidebarCollapsibleOnDesktop()
            ->widgets([
                
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsureEmailIsVerified::class,
            ])
            ->authMiddleware([Authenticate::class]);
            
           
    }

     public function canAccessPanel(User $user): bool
    {
        return $user->role === 'pemilik';
    }
}
