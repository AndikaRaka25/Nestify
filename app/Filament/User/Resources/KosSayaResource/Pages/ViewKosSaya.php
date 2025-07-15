<?php

namespace App\Filament\User\Resources\KosSayaResource\Pages;

use App\Filament\User\Resources\KosSayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKosSaya extends ViewRecord
{
    protected static string $resource = KosSayaResource::class;

    // Pastikan fungsi ini kosong agar tidak ada tombol di header
    protected function getHeaderActions(): array
    {
        return [];
    }
}
