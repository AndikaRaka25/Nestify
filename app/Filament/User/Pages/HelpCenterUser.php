<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class HelpCenterUser extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    // Memberitahu Filament untuk menggunakan file blade yang sama dari Langkah 1
    protected static string $view = 'filament.pages.help-center';

  public static function shouldRegisterNavigation(): bool
{
    return false;
}
    
}
