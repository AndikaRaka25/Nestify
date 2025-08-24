<?php

namespace App\Filament\Resources\PengajuanPenghuniResource\Pages;

use App\Filament\Resources\PengajuanPenghuniResource;
use App\Models\Penghuni;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class ListPengajuanPenghunis extends ListRecords
{
    protected static string $resource = PengajuanPenghuniResource::class;

    protected function getHeaderActions(): array
    {
        
        return [];
    }

    public function getTabs(): array
    {
        return [
            'pendaftar_baru' => ListRecords\Tab::make('Pendaftar Baru')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_penghuni', 'Pengajuan'))
                ->badge(Penghuni::query()->where('status_penghuni', 'Pengajuan')->count()),
            
            'pengajuan_berhenti' => ListRecords\Tab::make('Pengajuan Berhenti')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_penghuni', 'Pengajuan Berhenti'))
                ->badge(Penghuni::query()->where('status_penghuni', 'Pengajuan Berhenti')->count()),
        ];
    }


}
