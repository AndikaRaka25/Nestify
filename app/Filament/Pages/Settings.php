<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Settings extends Page
{
    // Menentukan file view yang akan digunakan oleh halaman ini
    protected static string $view = 'filament.pages.settings';

   
    protected static ?string $title = 'Settings';

    protected static ?string $slug = 'settings';

    public static function shouldRegisterNavigation(): bool
{
    return false;
}
}