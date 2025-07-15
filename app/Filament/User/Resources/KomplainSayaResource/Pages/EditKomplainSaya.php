<?php

namespace App\Filament\User\Resources\KomplainSayaResource\Pages;

use App\Filament\User\Resources\KomplainSayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomplainSaya extends EditRecord
{
    protected static string $resource = KomplainSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
