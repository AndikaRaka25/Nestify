<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTagihan extends CreateRecord
{
    protected static string $resource = TagihanResource::class;
    protected function getRedirectUrl(): string
    {
        // Mengembalikan URL ke halaman index (daftar) dari resource ini
        return static::getResource()::getUrl('index');
    }
}
