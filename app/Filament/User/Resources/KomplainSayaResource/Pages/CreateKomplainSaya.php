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
        // 1. Cari data lengkap penghuni yang sedang login
        $penghuni = Penghuni::where('email_penghuni', Auth::user()->email)->first();

        // 2. Jika data penghuni ditemukan, suntikkan ID-nya ke dalam data form
        if ($penghuni) {
            $data['penghuni_id'] = $penghuni->id;
            $data['properti_id'] = $penghuni->properti_id;
            $data['kamar_id'] = $penghuni->kamar_id;
        }

        // 3. Atur status awal komplain menjadi 'pending'
        $data['status'] = 'pending';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
