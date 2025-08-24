<?php

namespace App\Filament\User\Widgets;

use App\Models\Penghuni;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class InfoKosWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.info-kos-widget';
    protected int | string | array $columnSpan = 'full';

    public ?Penghuni $penghuni;

    public function mount(): void
    {
        
        $this->penghuni = Penghuni::where('email_penghuni', Auth::user()->email)
            ->where('status_penghuni', 'Aktif')
            ->with(['properti.user', 'kamar']) // Eager load relasi
            ->first();
    }
}
