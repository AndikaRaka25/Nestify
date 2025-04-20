<?php

namespace App\Filament\Resources\PropertiResource\Pages;

use App\Filament\Resources\PropertiResource;
use App\Models\Properti; 
use Filament\Actions; 
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;


class ViewProperti extends ViewRecord
{
    protected static string $resource = PropertiResource::class;

    protected function getActions(): array 
    {
        return [
            Actions\EditAction::make(), 
            Actions\DeleteAction::make()
                ->action(function () {
                    $properti = $this->getRecord(); 
                    $properti->delete();
                    Notification::make()
                        ->title('Data Properti Berhasil Dihapus')
                        ->success()
                        ->send();
                    $this->redirect(PropertiResource::getUrl('index'));
                })
                ->requiresConfirmation(),
        ];
    }

    // HAPUS SELURUH METHOD getFormSchema() DARI SINI
    // protected function getFormSchema(): array
    // {
    //     // ... KODE LAMA DI SINI ...
    // }
}