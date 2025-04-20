<?php

namespace App\Filament\Resources\PengajuanPenghuniResource\Pages;

use App\Filament\Resources\PengajuanPenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengajuanPenghuni extends CreateRecord
{
    protected static string $resource = PengajuanPenghuniResource::class;
    protected function getRedirectUrl(): string
    {
        // Mengembalikan URL ke halaman index (daftar) dari resource ini
        return static::getResource()::getUrl('index');
    }
}
