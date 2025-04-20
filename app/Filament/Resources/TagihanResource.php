<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Filament\Resources\TagihanResource\RelationManagers;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tables\Columns\DateColumn;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public function getTabs(): array
    {
        return [
            'Tagihan' => [
                'label' => 'Tagihan',
                'icon' => 'heroicon-o-document-text',
            ],
            'History' => [
                'label' => 'History',
                'icon' => 'heroicon-o-clock',
            ],
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('penghuni_id')
                            ->relationship('penyewa', 'name')
                            ->label('Penyewa')
                            ->required(),
                        Forms\Components\Select::make('tipe_kamar_id')
                            ->relationship('tipeKamar', 'name')
                            ->label('Tipe Kamar')
                            ->required(),
                        Forms\Components\Select::make('properti_id')
                            ->relationship('properti', 'name')
                            ->label('Properti')
                            ->required(),
                        Forms\Components\TextInput::make('periode_pembayaran')
                            ->label('Periode Pembayaran')
                            ->required(),
                        Forms\Components\TextInput::make('jatuh_tempo')
                            ->label('Jatuh Tempo')
                            ->date()
                            ->required(),
                        Forms\Components\TextInput::make('total_tagihan')
                            ->label('Total Tagihan')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Belum Bayar' => 'Belum Bayar',
                                'Butuh Konfirmasi' => 'Butuh Konfirmasi',
                                'Lunas' => 'Lunas',
                            ])
                            ->label('Status Pembayaran'),
                    ]),
            ])->columns(2)
            ->columns([
                'sm' => 2,
                'lg' => 2,
                'xl' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Mengambil nama penyewa melalui relasi
            Tables\Columns\TextColumn::make('penghuni.name')
                ->label('Nama Penyewa')
                ->sortable(),
            // Mengambil tipe kamar melalui relasi
            Tables\Columns\TextColumn::make('tipe_kamar.name')
                ->label('Tipe Kamar')
                ->sortable(),
            // Mengambil properti melalui relasi
            Tables\Columns\TextColumn::make('nama_properti.name')
                ->label('Properti')
                ->sortable(),
            // Periode pembayaran (misalnya: format "Bulan/Tahun" atau tanggal mulai-selesai)
            Tables\Columns\TextColumn::make('periode_pembayaran')
                ->label('Periode Pembayaran')
                ->sortable(),
            // Status pembayaran yang akan ditampilkan dengan badge berwarna
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status Pembayaran')
                ->colors([
                    'warning' => 'Belum Bayar',
                    'secondary' => 'Butuh Konfirmasi',
                    'success' => 'Lunas',
                ]),
            // Jatuh tempo (ditampilkan sebagai tanggal)
            Tables\Columns\TextColumn::make('jatuh_tempo')
                ->label('Jatuh Tempo')
                ->sortable(),
            // Total tagihan (format mata uang)
            Tables\Columns\TextColumn::make('total_tagihan')
                ->label('Total Tagihan')
                ->money('IDR', true)
                ->sortable(),
        ])
        ->filters([
            // Filter untuk menyaring data berdasarkan status
            Tables\Filters\Filter::make('status')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('Status Pembayaran')
                        ->options([
                            'Belum Bayar' => 'Belum Bayar',
                            'Butuh Konfirmasi' => 'Butuh Konfirmasi',
                            'Lunas' => 'Lunas',
                        ])
                ])
                ->query(function ($query, array $data) {
                    if ($data['status']) {
                        $query->where('status', $data['status']);
                    }
                })
                ->indicateUsing(fn (array $data): ?string => $data['status'] ? "Status: {$data['status']}" : null),
        ]);
}
public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagihans::route('/'),
            'create' => Pages\CreateTagihan::route('/create'),
            'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
