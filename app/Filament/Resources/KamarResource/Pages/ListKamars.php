<?php

namespace App\Filament\Resources\KamarResource\Pages;

use App\Filament\Resources\KamarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\KamarResource\Widgets\PropertiFilter;

class ListKamars extends ListRecords
{
    protected static string $resource = KamarResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Kamar Baru'),
        ];
    }

}
