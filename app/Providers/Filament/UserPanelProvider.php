<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\User;
use App\Filament\User\Widgets\AccountWidget;
use App\Filament\User\Widgets\StatsOverview;
use App\Filament\User\Widgets\KamarOverview;
use App\Filament\User\Widgets\PropertiOverview;
use App\Filament\User\Pages\Dashboard;
use App\Filament\User\Pages\SettingsUser;
use Filament\Navigation\MenuItem;
use App\Filament\User\Pages\HelpCenterUser;
use App\Filament\User\Pages\PenyewaDashboard;


class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn (): string => SettingsUser::getUrl())
                    ->icon('heroicon-o-cog-6-tooth'),

                MenuItem::make()
                ->label('Bantuan & Dukungan')
                ->url(fn (): string => HelpCenterUser::getUrl())
                ->icon('heroicon-o-question-mark-circle'),
            ])
            ->brandName('Nestify') 
            ->brandLogo(asset('storage/landing_page/logo_nestify.png')) 
            ->brandLogoHeight('2rem') 
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\\Filament\\User\\Resources')
            ->discoverPages(in: app_path('Filament/User/PagesUser'), for: 'App\\Filament\\User\\PagesUser')
            ->dashboard(PenyewaDashboard::class)
            ->pages([
                Pages\Dashboard::class,
                SettingsUser::class,
                HelpCenterUser::class,
                
            ])
            
            ->discoverWidgets(in: app_path('Filament/User/WidgetsUser'), for: 'App\\Filament\\User\\WidgetsUser')
            ->spa()
            ->sidebarCollapsibleOnDesktop()
            ->widgets([
                Widgets\AccountWidget::class,
              
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
     public function canAccessPanel(User $user): bool
    {
        return $user->role === 'penyewa';
    }
}
