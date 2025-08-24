<?php

namespace App\Filament\Resources\TagihanResource\Widgets;

use App\Models\Penghuni;
use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TagihanStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        
        $jumlahPenghuniAktif = Penghuni::where('status_penghuni', 'Aktif')->count();

        
        $pendapatanBulanIni = Tagihan::where('status', 'Lunas')
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('total_tagihan');

        
        $jumlahTagihanAktif = Tagihan::whereIn('status', ['Belum Bayar', 'Butuh Konfirmasi'])->count();
        
    
        $totalTagihanAktif = Tagihan::whereIn('status', ['Belum Bayar', 'Butuh Konfirmasi'])->sum('total_tagihan');

        return [
            Stat::make('Jumlah Penghuni Aktif', $jumlahPenghuniAktif)
                ->description('Total semua penghuni yang aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description('Total pendapatan lunas bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Jumlah Tagihan Belum Lunas', $jumlahTagihanAktif)
                ->description('Total tagihan dengan status Belum Bayar & Butuh Konfirmasi')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('warning'),
            Stat::make('Total Nominal Belum Lunas', 'Rp ' . number_format($totalTagihanAktif, 0, ',', '.'))
                ->description('Nominal dari semua tagihan yang belum lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
        ];
    }
}
