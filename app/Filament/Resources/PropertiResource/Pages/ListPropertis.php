<?php

namespace App\Filament\Resources\PropertiResource\Pages;

use App\Filament\Resources\PropertiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPropertis extends ListRecords
{
    protected static string $resource = PropertiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Properti Baru'),
        ];
    }
    
    // Optional: Jika ingin menambahkan widget statistik di atas tabel
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\PropertiOverview::class,
        ];
    }
}