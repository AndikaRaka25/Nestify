<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;

use App\Models\Tagihan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TagihanResource\Widgets\TagihanChart;


class ListTagihans extends ListRecords
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TagihanResource\Widgets\TagihanStatsOverview::class,
            TagihanChart::class,
            TagihanResource\Widgets\TotalTagihanChart::class,
           
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => ListRecords\Tab::make('Semua Tagihan')
                ->badge(Tagihan::count()),
            'belum_bayar' => ListRecords\Tab::make('Belum Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Belum Bayar'))
                ->badge(Tagihan::where('status', 'Belum Bayar')->count()),
            'butuh_konfirmasi' => ListRecords\Tab::make('Butuh Konfirmasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Butuh Konfirmasi'))
                ->badge(Tagihan::where('status', 'Butuh Konfirmasi')->count()),
            'lunas' => ListRecords\Tab::make('Lunas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Lunas'))
                ->badge(Tagihan::where('status', 'Lunas')->count()),
        ];
    }
}
