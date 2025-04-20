<?php

namespace App\Filament\Resources\KelolaKomplainResource\Pages;

use App\Filament\Resources\KelolaKomplainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelolaKomplains extends ListRecords
{
    protected static string $resource = KelolaKomplainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
