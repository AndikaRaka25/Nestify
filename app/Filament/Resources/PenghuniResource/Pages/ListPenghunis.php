<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Penghuni;

class ListPenghunis extends ListRecords
{
    protected static string $resource = PenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Penghuni Baru'),
        ];
    }
    public function getTabs(): array
    {
        return [
            'aktif' => ListRecords\Tab::make('Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_penghuni', 'Aktif'))
                ->badge(Penghuni::query()->where('status_penghuni', 'Aktif')->count()),
            
            'tidak_aktif' => ListRecords\Tab::make('Tidak Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_penghuni', 'Tidak Aktif'))
                ->badge(Penghuni::query()->where('status_penghuni', 'Tidak Aktif')->count()),
            'semua' => ListRecords\Tab::make('Semua Penghuni')
                ->badge(Penghuni::query()->count()), // Menghitung semua data
        ];
    }
}
