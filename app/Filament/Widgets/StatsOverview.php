<?php

namespace App\Filament\Widgets;

use App\Models\Penghuni; // Import model Penghuni
use App\Models\Tagihan; // Import model Tagihan
use App\Models\KelolaKomplain; // Import model KelolaKomplain
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Mengatur agar widget ini muncul di bagian atas dashboard
    protected static ?int $sort = 0; 
    
    protected function getStats(): array
    {
        // 1. Menghitung jumlah pengajuan yang menunggu (Pengajuan & Pengajuan Berhenti)
        $pengajuanMenunggu = Penghuni::whereIn('status_penghuni', ['Pengajuan', 'Pengajuan Berhenti'])->count();

        // 2. Menghitung jumlah tagihan yang belum lunas (Belum Bayar & Butuh Konfirmasi)
        $tagihanBelumLunas = Tagihan::whereIn('status', ['Belum Bayar', 'Butuh Konfirmasi'])->count();

        // 3. Menghitung jumlah komplain yang aktif (pending & proses)
        $komplainAktif = KelolaKomplain::whereIn('status', ['pending', 'proses'])->count();

        return [
            // Statistik untuk Pengajuan Menunggu
            Stat::make('Pengajuan Menunggu', $pengajuanMenunggu)
                ->description('Pengajuan masuk & berhenti sewa')
                ->descriptionIcon('heroicon-o-inbox-arrow-down')
                ->color($pengajuanMenunggu > 0 ? 'warning' : 'success'), // Warna kuning jika ada, hijau jika tidak ada

            // Statistik untuk Tagihan Penyewa (Belum Lunas)
            Stat::make('Tagihan Belum Lunas', $tagihanBelumLunas)
                ->description('Tagihan belum bayar & butuh konfirmasi')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($tagihanBelumLunas > 0 ? 'danger' : 'success'), // Warna merah jika ada, hijau jika tidak ada

            // Statistik untuk Komplain Aktif
            Stat::make('Komplain Aktif', $komplainAktif)
                ->description('Komplain pending & dalam proses')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color($komplainAktif > 0 ? 'info' : 'success'), // Warna biru jika ada, hijau jika tidak ada
        ];
    }
}

