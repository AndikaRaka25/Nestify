<?php

namespace App\Filament\User\Widgets;

use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TagihanAktifWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $tagihan = Tagihan::whereHas('penghuni', fn($q) => $q->where('email_penghuni', Auth::user()->email))
            ->where('status', 'Belum Bayar')
            ->orderBy('jatuh_tempo', 'asc')
            ->first();

        if (!$tagihan) {
            return []; // Jangan tampilkan apa-apa jika tidak ada tagihan aktif
        }
        
        $jatuhTempo = Carbon::parse($tagihan->jatuh_tempo);
        $isOverdue = $jatuhTempo->isPast();

        return [
            Stat::make('Tagihan Berikutnya', 'Rp ' . number_format($tagihan->total_tagihan))
                ->description($tagihan->periode)
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
            Stat::make('Jatuh Tempo', $jatuhTempo->isoFormat('D MMMM YYYY'))
                ->description($isOverdue ? 'SUDAH LEWAT!' : $jatuhTempo->diffForHumans())
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($isOverdue ? 'danger' : 'warning'),
        ];
    }
}