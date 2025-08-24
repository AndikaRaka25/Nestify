<?php

namespace App\Filament\Resources\TagihanResource\Widgets;

use App\Models\Properti;
use App\Models\Tagihan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TagihanChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Lunas';
    protected static ?string $description = 'Menampilkan pendapatan dari tagihan lunas dalam 6 bulan terakhir.';

     protected int | string | array $columnSpan = 1;

    
    public ?string $filter = null; 

    
    protected function getFilters(): ?array
    {
        $propertiOptions = Properti::where('user_id', Auth::id())
                                   ->pluck('nama_properti', 'id')
                                   ->toArray();
                                   
        return [null => 'Semua Properti'] + $propertiOptions;
    }

    
    public function updatedFilter(): void
    {
        $this->updateChartData();
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $labels = [];
        $dataPoints = [];

        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $dataPoints[$month->format('Y-m')] = 0;
        }

        $query = Tagihan::query()
            ->where('status', 'Lunas')
            ->whereBetween('tanggal_bayar', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()]);

        
        if (!is_null($activeFilter) && $activeFilter !== '') {
            $query->where('properti_id', $activeFilter);
        } else {
            $userPropertiIds = Properti::where('user_id', Auth::id())->pluck('id');
            $query->whereIn('properti_id', $userPropertiIds);
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
                $dataPoints[$item->bulan] = (float) $item->total;
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
