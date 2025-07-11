<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
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

    /**
     * Method ini adalah "jembatan" yang akan berjalan tepat sebelum
     * formulir diisi dengan data. Di sinilah kita akan secara manual
     * memuat data relasi yang kita butuhkan.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // 1. Ambil record Tagihan yang lengkap dengan relasinya
        $tagihan = static::getResource()::getEloquentQuery()->with(['penghuni', 'properti', 'kamar'])->find($data['id']);

        // 2. Jika tagihan ditemukan, "suntikkan" data relasi ke dalam array data form
        if ($tagihan) {
            $data['penghuni.nama_penghuni'] = $tagihan->penghuni?->nama_penghuni;
            $data['properti.nama_properti'] = $tagihan->properti?->nama_properti;
            $data['kamar.nama_kamar'] = $tagihan->kamar?->nama_kamar;
        }
        
        return $data;
    }
}
