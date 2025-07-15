<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HelpCenter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    // Memberitahu Filament untuk menggunakan file blade yang kita buat di Langkah 1
    protected static string $view = 'filament.pages.help-center';

   public static function shouldRegisterNavigation(): bool
{
    return false;
}
}
