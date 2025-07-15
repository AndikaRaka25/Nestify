<?php

namespace App\Filament\User\Resources\KomplainSayaResource\Pages;

use App\Filament\User\Resources\KomplainSayaResource;
use App\Models\Penghuni;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateKomplainSaya extends CreateRecord
{
    protected static string $resource = KomplainSayaResource::class;

    /**
     * ✅ Method ini adalah "mesin" otomatisnya.
     * Sebelum data dari form disimpan, kita akan menyuntikkan data penting.
     */
   protected function mutateFormDataBeforeCreate(array $data): array
{
        // Langkah 1: Dapatkan email dari pengguna yang sedang login.
        // Ini adalah satu-satunya penghubung yang valid.
        $userEmail = Auth::user()->email;

        // Langkah 2: Cari data sewa (penghuni) yang statusnya "Aktif"
        // menggunakan email tersebut.
        $penghuniAktif = Penghuni::where('email_penghuni', $userEmail)
                                ->where('status_penghuni', 'Aktif')
                                ->first();

        // Langkah 3: Pastikan data penghuni aktif ditemukan. Jika ya,
        // ambil semua ID yang diperlukan dari sana.
        if ($penghuniAktif) {
            $data['penghuni_id'] = $penghuniAktif->id;
            $data['properti_id'] = $penghuniAktif->properti_id;
            $data['kamar_id'] = $penghuniAktif->kamar_id; // kamar_id PASTI terisi
        }

        // Langkah 4: Atur status awal komplain menjadi 'pending'.
        $data['status'] = 'pending';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
