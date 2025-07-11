<?php

namespace App\Filament\Resources\KelolaKomplainResource\Pages;

use App\Filament\Resources\KelolaKomplainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListKelolaKomplains extends ListRecords
{
    protected static string $resource = KelolaKomplainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol create dinonaktifkan karena komplain dibuat oleh penyewa
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => ListRecords\Tab::make('Semua'),
            'aktif' => ListRecords\Tab::make('Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['pending', 'proses']))
                ->badge(fn (Builder $query) => $query->whereIn('status', ['pending', 'proses'])->count()),
            'selesai' => ListRecords\Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'selesai'))
                ->badge(fn (Builder $query) => $query->where('status', 'selesai')->count()),
        ];
    }
}
