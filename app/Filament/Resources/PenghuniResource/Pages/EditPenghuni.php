<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenghuni extends EditRecord
{
    protected static string $resource = PenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['durasi_sewa']) && !empty($data['durasi_sewa'])) {
            $parts = explode(' ', $data['durasi_sewa'], 2);
            
            if (count($parts) === 2) {
                $data['durasi_angka'] = $parts[0];
                $data['durasi_unit'] = strtolower($parts[1]); 
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['durasi_angka']) && !empty($data['durasi_angka']) && isset($data['durasi_unit'])) {
            $data['durasi_sewa'] = $data['durasi_angka'] . ' ' . ucfirst($data['durasi_unit']);
        }

        // Hapus field virtual agar tidak ikut disimpan ke database
        unset($data['durasi_angka']);
        unset($data['durasi_unit']);

        return $data;
    }
}
