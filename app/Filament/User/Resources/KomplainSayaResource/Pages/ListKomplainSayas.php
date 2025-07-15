<?php

namespace App\Filament\User\Resources\KomplainSayaResource\Pages;

use App\Filament\User\Resources\KomplainSayaResource;
use App\Models\Penghuni;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListKomplainSayas extends ListRecords
{
    protected static string $resource = KomplainSayaResource::class;

    // Kita tidak lagi memerlukan properti $isAPenghuni atau metode mount().
    // Semua pengecekan akan dilakukan secara langsung.

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Komplain Baru')
                // ✅ Pengecekan dilakukan langsung di sini
                ->visible(function (): bool {
                    return Penghuni::where('email_penghuni', Auth::user()->email)
                                   ->where('status_penghuni', 'Aktif')
                                   ->exists();
                }),
        ];
    }

    public function getTabs(): array
    {
        // ✅ Pengecekan dilakukan langsung di sini
        if (!Penghuni::where('email_penghuni', Auth::user()->email)->where('status_penghuni', 'Aktif')->exists()) {
            return [];
        }

        return [
            'proses' => ListRecords\Tab::make('Dalam Proses')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['pending', 'proses']))
                ->badge(static::getResource()::getEloquentQuery()->whereIn('status', ['pending', 'proses'])->count()),
            
            'selesai' => ListRecords\Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'selesai'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', 'selesai')->count()),
        ];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        // ✅ Pengecekan dilakukan langsung di sini
        if (Penghuni::where('email_penghuni', Auth::user()->email)->where('status_penghuni', 'Aktif')->exists()) {
            return 'Belum Ada Komplain';
        }

        return 'Fitur Komplain Belum Tersedia';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        // ✅ Pengecekan dilakukan langsung di sini
        if (!Penghuni::where('email_penghuni', Auth::user()->email)->where('status_penghuni', 'Aktif')->exists()) {
            return 'Anda harus terdaftar sebagai penyewa aktif untuk dapat menggunakan fitur ini.';
        }

        return null;
    }

    protected function getTableEmptyStateActions(): array
    {
        // ✅ Pengecekan dilakukan langsung di sini
        if (!Penghuni::where('email_penghuni', Auth::user()->email)->where('status_penghuni', 'Aktif')->exists()) {
            return [
                Actions\Action::make('cari_kos')
                    ->label('Cari & Daftar Kos Sekarang')
                    ->url(\App\Filament\User\Resources\KosSayaResource::getUrl('browse-properti'))
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary'),
            ];
        }
        
        return [];
    }
}
