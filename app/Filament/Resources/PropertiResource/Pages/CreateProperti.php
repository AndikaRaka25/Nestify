<?php

namespace App\Filament\Resources\PropertiResource\Pages;

use App\Filament\Resources\PropertiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini di-import

class CreateProperti extends CreateRecord
{
    protected static string $resource = PropertiResource::class;

    /**
     * Ini adalah metode intervensi langsung kita.
     * Kode ini akan dijalankan TEPAT SEBELUM data dari formulir
     * disimpan ke database.
     *
     * @param  array  $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kita menyisipkan user_id dari pengguna yang sedang login
        // langsung ke dalam data yang akan disimpan.
        $data['user_id'] = Auth::id();
 
        return $data;
    }
}
