<?php

namespace App\Filament\User\Resources\KomplainSayaResource\Pages;

use App\Filament\User\Resources\KomplainSayaResource;
use App\Models\KelolaKomplain; // 💡 Import model
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKomplainSaya extends ViewRecord
{
    protected static string $resource = KomplainSayaResource::class;

    /**
     * ✅ --- PERBAIKAN UTAMA DI SINI --- ✅
     * Metode ini akan memanipulasi data SEBELUM form ditampilkan.
     */
    protected function mutateFormDataBeforeView(array $data): array
    {
        // 1. Ambil data komplain yang sedang dilihat
        $komplain = KelolaKomplain::find($data['id']);

        // 2. Jika komplain ada dan memiliki relasi penghuni, ambil nama penghuninya
        if ($komplain && $komplain->penghuni) {
            $data['nama_pelapor'] = $komplain->penghuni->nama_penghuni;
        }

        // 3. Kembalikan data yang sudah dimodifikasi
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return []; // Tetap kosong untuk menonaktifkan tombol Edit/Delete
    }
}
