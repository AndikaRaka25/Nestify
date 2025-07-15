<?php

namespace App\Filament\User\Resources\TagihanResource\Pages;

use App\Filament\User\Resources\TagihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTagihan extends EditRecord
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
