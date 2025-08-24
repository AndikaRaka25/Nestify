<?php

namespace App\Filament\Resources\KelolaKomplainResource\Pages;

use App\Filament\Resources\KelolaKomplainResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKelolaKomplain extends CreateRecord
{
    protected static string $resource = KelolaKomplainResource::class;
    protected function getRedirectUrl(): string
    {
        
        return static::getResource()::getUrl('index');
    }
}
