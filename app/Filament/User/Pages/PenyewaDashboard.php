<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\User\Widgets\InfoKosWidget;
use App\Filament\User\Widgets\TagihanAktifWidget;
use App\Filament\User\Widgets\KomplainAktifWidget;

class PenyewaDashboard extends BaseDashboard
{
    // Mengubah judul halaman
    public function getTitle(): string
    {
        return 'Dasbor Saya';
    }

    // Mengatur tata letak dasbor
    public function getColumns(): int | string | array
    {
        return 'full'; // Menggunakan lebar penuh untuk fleksibilitas
    }

    
    public function getWidgets(): array
    {
        return [
        InfoKosWidget::class,
            TagihanAktifWidget::class,
            KomplainAktifWidget::class,
        ];
    }
}
