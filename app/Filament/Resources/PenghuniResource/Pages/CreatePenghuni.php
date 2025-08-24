<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenghuni extends CreateRecord
{
    protected static string $resource = PenghuniResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['durasi_angka']) && !empty($data['durasi_angka']) && isset($data['durasi_unit'])) {
            $data['durasi_sewa'] = $data['durasi_angka'] . ' ' . ucfirst($data['durasi_unit']);
        }

        unset($data['durasi_angka']);
        unset($data['durasi_unit']);

        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
