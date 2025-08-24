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

    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        
        $tagihan = static::getResource()::getEloquentQuery()->with(['penghuni', 'properti', 'kamar'])->find($data['id']);

        
        if ($tagihan) {
            $data['penghuni.nama_penghuni'] = $tagihan->penghuni?->nama_penghuni;
            $data['properti.nama_properti'] = $tagihan->properti?->nama_properti;
            $data['kamar.nama_kamar'] = $tagihan->kamar?->nama_kamar;
        }
        
        return $data;
    }
}
