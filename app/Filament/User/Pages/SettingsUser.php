<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class SettingsUser extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    // Memberitahu Filament untuk menggunakan file blade yang sama dari Langkah 1
    protected static string $view = 'filament.pages.settings';

    // Mengatur urutan menu agar berada di bawah

 public static function shouldRegisterNavigation(): bool
{
    return false;
}
    // Judul yang akan tampil di halaman
    
}
