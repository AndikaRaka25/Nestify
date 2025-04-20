<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenghuni extends CreateRecord
{
    protected static string $resource = PenghuniResource::class;
    protected function getRedirectUrl(): string
    {
        // Mengembalikan URL ke halaman index (daftar) dari resource ini
        return static::getResource()::getUrl('index');
    }
}
