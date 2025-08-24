<?php

namespace App\Filament\Resources\TagihanResource\Widgets;

use App\Models\Tagihan;
use Filament\Widgets\ChartWidget;

class TotalTagihanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Keuangan';
    protected static ?string $maxHeight = '300px';

    
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
       
        $totalLunas = Tagihan::where('status', 'Lunas')->sum('total_tagihan');

        
        $totalBelumLunas = Tagihan::whereIn('status', ['Belum Bayar', 'Butuh Konfirmasi'])->sum('total_tagihan');

        return [
            'datasets' => [
                [
                    'label' => 'Total Tagihan',
                    'data' => [$totalLunas, $totalBelumLunas],
                    'backgroundColor' => [
                        '#22c55e', // Hijau untuk Lunas
                        '#ef4444', // Merah untuk Belum Lunas
                    ],
                ],
            ],
            'labels' => [
                'Lunas: Rp ' . number_format($totalLunas, 0, ',', '.'),
                'Belum Lunas: Rp ' . number_format($totalBelumLunas, 0, ',', '.'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
