<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class HelpCenterUser extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    
    protected static string $view = 'filament.pages.help-center';

  public static function shouldRegisterNavigation(): bool
{
    return false;
}
    
}
