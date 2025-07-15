<?php

namespace App\Filament\User\Resources\TagihanResource\Pages;

use App\Filament\User\Resources\TagihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTagihans extends ListRecords
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Create dinonaktifkan karena tagihan dibuat oleh admin
        ];
    }

    /**
     * ✅ Di sinilah kita membuat 4 Tab yang Anda minta.
     * Nama-nama tab sudah saya sesuaikan agar lebih intuitif.
     */
    public function getTabs(): array
    {
        // Mendapatkan query dasar yang sudah difilter untuk user ini saja
        $baseQuery = static::getResource()::getEloquentQuery();

        return [
            'semua' => ListRecords\Tab::make('Semua Tagihan')
                ->badge($baseQuery->count()),

            'belum_lunas' => ListRecords\Tab::make('Belum Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Belum Bayar'))
                ->badge($baseQuery->clone()->where('status', 'Belum Bayar')->count()),
            
            'proses' => ListRecords\Tab::make('Menunggu Konfirmasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Butuh Konfirmasi'))
                ->badge($baseQuery->clone()->where('status', 'Butuh Konfirmasi')->count()),

            'lunas' => ListRecords\Tab::make('Lunas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Lunas'))
                ->badge($baseQuery->clone()->where('status', 'Lunas')->count()),
        ];
    }
}
