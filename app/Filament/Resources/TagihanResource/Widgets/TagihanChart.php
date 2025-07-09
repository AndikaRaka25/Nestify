<?php

namespace App\Filament\Resources\TagihanResource\Widgets;

use App\Models\Properti;
use App\Models\Tagihan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TagihanChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan Lunas (12 Bulan Terakhir)';
    protected static ?string $maxHeight = '300px';

    // --- PERUBAHAN DI SINI ---
    // Mengatur agar widget ini memakan 2 kolom dari total 3 kolom grid
    protected int | string | array $columnSpan = 1;

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $propertiOptions = Properti::pluck('nama_properti', 'id')->toArray();
        return array_merge(['all' => 'Semua Properti'], $propertiOptions);
    }

    protected function getData(): array
    {
        $labels = [];
        $dataPoints = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $dataPoints[$month->format('Y-m')] = 0;
        }

        $query = Tagihan::query()
            ->where('status', 'Lunas')
            ->whereBetween('tanggal_bayar', [now()->subYear(), now()]);

        if ($this->filter !== 'all') {
            $query->where('properti_id', $this->filter);
        }

        $pendapatanPerBulan = $query
            ->select(
                DB::raw('SUM(total_tagihan) as total'),
                DB::raw("DATE_FORMAT(tanggal_bayar, '%Y-%m') as bulan")
            )
            ->groupBy('bulan')
            ->get();

        foreach ($pendapatanPerBulan as $item) {
            if (isset($dataPoints[$item->bulan])) {
                $dataPoints[$item->bulan] = $item->total;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Lunas',
                    'data' => array_values($dataPoints),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
