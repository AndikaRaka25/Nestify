<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
use App\Filament\Resources\TagihanResource\Widgets\TagihanChart;
use App\Filament\Resources\TagihanResource\Widgets\TagihanStatsOverview;
// Import widget baru
use App\Filament\Resources\TagihanResource\Widgets\TotalTagihanChart; 
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTagihans extends ListRecords
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol create dinonaktifkan
        ];
    }

    /**
     * Method untuk menampilkan semua widget di bagian atas halaman.
     * Urutan di sini menentukan urutan tampilan.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            TagihanStatsOverview::class,
            TagihanChart::class,
            TotalTagihanChart::class, 
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => ListRecords\Tab::make('Semua Tagihan'),
            'belum_bayar' => ListRecords\Tab::make('Belum Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Belum Bayar')),
            'butuh_konfirmasi' => ListRecords\Tab::make('Butuh Konfirmasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Butuh Konfirmasi')),
            'lunas' => ListRecords\Tab::make('Lunas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Lunas')),
        ];
    }
}
