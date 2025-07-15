<?php

namespace App\Filament\Resources\PengajuanPenghuniResource\Pages;

use App\Filament\Resources\PengajuanPenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPengajuanPenghuni extends ViewRecord
{
    protected static string $resource = PengajuanPenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
