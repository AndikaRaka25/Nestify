<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class SettingsUser extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    
    protected static string $view = 'filament.pages.settings';


 public static function shouldRegisterNavigation(): bool
{
    return false;
}
    
    
}
