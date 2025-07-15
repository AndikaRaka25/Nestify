<?php

namespace App\Filament\User\Resources\KosSayaResource\Pages;

use App\Filament\User\Resources\KosSayaResource;
use App\Models\Penghuni;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class ListKosSayas extends ListRecords
{
    protected static string $resource = KosSayaResource::class;

    // ✅ Kita akan menggunakan view kustom kita
    protected static string $view = 'filament.user.resources.kos-saya-resource.pages.list-kos-sayas-custom';
    
    // Kita tidak lagi memerlukan properti publik di sini

    protected function getHeaderActions(): array
    {
        // Tombol ini akan kita kontrol visibilitasnya langsung di file Blade
        return [
            Actions\CreateAction::make()->label('Buat Kos Baru'), // Ini seharusnya tidak ada, tapi kita biarkan untuk contoh
        ];
    }
    
    /**
     * ✅ Mengirim data status penghuni secara eksplisit ke view.
     */
    protected function getViewData(): array
    {
        $isAPenghuni = Penghuni::where('email_penghuni', Auth::user()->email)
                                ->whereIn('status_penghuni', ['Aktif', 'Pengajuan Berhenti'])
                                ->exists();

        return [
            'isAPenghuni' => $isAPenghuni,
        ];
    }
}
