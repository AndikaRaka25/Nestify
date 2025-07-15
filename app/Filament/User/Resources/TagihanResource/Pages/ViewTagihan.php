<?php

namespace App\Filament\User\Resources\TagihanResource\Pages;

use App\Filament\User\Resources\TagihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTagihan extends ViewRecord
{
    protected static string $resource = TagihanResource::class;

    /**
     * Menonaktifkan semua tombol aksi di header halaman detail.
     * Penyewa hanya boleh melihat, tidak mengedit dari sini.
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}