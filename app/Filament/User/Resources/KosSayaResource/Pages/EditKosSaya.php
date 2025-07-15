<?php

namespace App\Filament\User\Resources\KosSayaResource\Pages;

use App\Filament\User\Resources\KosSayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKosSaya extends EditRecord
{
    protected static string $resource = KosSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
