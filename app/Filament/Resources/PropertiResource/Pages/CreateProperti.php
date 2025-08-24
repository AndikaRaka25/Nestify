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
     *
     *
     * @param  array  $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $data['user_id'] = Auth::id();
 
        return $data;
    }
}
