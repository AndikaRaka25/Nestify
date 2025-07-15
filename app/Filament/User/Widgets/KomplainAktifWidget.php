<?php
namespace App\Filament\User\Widgets;

use App\Models\KelolaKomplain;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class KomplainAktifWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                KelolaKomplain::query()
                    ->whereHas('penghuni', fn($q) => $q->where('email_penghuni', Auth::user()->email))
                    ->whereIn('status', ['pending', 'proses'])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('judul'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['warning' => 'pending', 'primary' => 'proses']),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat')->since(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Tidak ada komplain aktif saat ini.');
    }
}