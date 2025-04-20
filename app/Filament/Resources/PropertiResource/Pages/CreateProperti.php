<?php

namespace App\Filament\Resources\PropertiResource\Pages;

use App\Filament\Resources\PropertiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProperti extends CreateRecord
{
    protected static string $resource = PropertiResource::class;
    protected function getRedirectUrl(): string
    {
        // Mengembalikan URL ke halaman index (daftar) dari resource ini
        return static::getResource()::getUrl('index');
    }
}
