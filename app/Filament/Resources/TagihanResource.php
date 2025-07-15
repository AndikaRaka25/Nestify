<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $label = 'Manajemen Tagihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- PERBAIKAN DI SINI: Menggunakan Placeholder ---
                Forms\Components\Placeholder::make('nama_penghuni')
                    ->label('Nama Penghuni')
                    ->content(fn (?Tagihan $record): string => $record?->penghuni?->nama_penghuni ?? '-'),

                Forms\Components\Placeholder::make('nama_properti')
                    ->label('Properti')
                    ->content(fn (?Tagihan $record): string => $record?->properti?->nama_properti ?? '-'),

                Forms\Components\Placeholder::make('nama_kamar')
                    ->label('Kamar')
                    ->content(fn (?Tagihan $record): string => $record?->kamar?->nama_kamar ?? '-'),
                
                // Kolom lain yang tidak perlu diedit juga lebih baik menggunakan Placeholder
                Forms\Components\Placeholder::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->content(fn (?Tagihan $record): string => $record?->invoice_number ?? '-'),
                
                Forms\Components\Placeholder::make('diskon')
                    ->label('Diskon yang Digunakan')
                    // 🛑 Logika ->visible() DIHAPUS dari sini
                    ->content(function (?Tagihan $record): string {
                        // Cek jika ada data diskon yang tersimpan di dalam record tagihan
                        if ($discount = $record?->applied_discount) {
                            $nilai = $discount['nilai_diskon'];
                            $jenis = $discount['jenis_diskon'];
                            
                            // Format tampilan berdasarkan jenis diskon
                            $formattedValue = $jenis === 'persen' ? "{$nilai}%" : 'Rp ' . number_format($nilai, 0, ',', '.');
                            
                            // Kembalikan detail diskon
                            return "{$discount['kode_promo']} ({$formattedValue})";
                        }
                        
                        // Jika tidak ada diskon yang digunakan, tampilkan strip
                        return '-';
                    }),
                
                Forms\Components\Placeholder::make('periode')
                    ->label('Periode')
                    ->content(fn (?Tagihan $record): string => $record?->periode ?? '-'),

                Forms\Components\TextInput::make('total_tagihan')
                    ->label('Total Tagihan')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(), // Dibiarkan sebagai TextInput karena mungkin perlu diedit

                Forms\Components\DatePicker::make('jatuh_tempo')->label('Jatuh Tempo')->disabled(),
                
                // Kolom yang bisa diubah
                Forms\Components\Select::make('status')->options(['Belum Bayar' => 'Belum Bayar', 'Butuh Konfirmasi' => 'Butuh Konfirmasi', 'Lunas' => 'Lunas']),
                
                Forms\Components\FileUpload::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->disabled(),
            ])->columns(2); // Mengatur layout form menjadi 2 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Invoice')->searchable(),
                Tables\Columns\TextColumn::make('penghuni.nama_penghuni')->label('Penghuni')->searchable(),
                Tables\Columns\TextColumn::make('periode')->sortable(),
                Tables\Columns\TextColumn::make('total_tagihan')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Belum Bayar' => 'warning', 'Butuh Konfirmasi' => 'info', 'Lunas' => 'success', default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('jatuh_tempo')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('properti_id')
                    ->relationship('properti', 'nama_properti')
                    ->placeholder('Pilih Properti')
                    ->multiple()
                    ->preload()
                    ->columnSpan(2)
                    ->label('Properti'),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Lihat/Ubah Status'),
                Action::make('konfirmasi')
                    ->label('Konfirmasi Lunas')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Tagihan $record): bool => $record->status === 'Butuh Konfirmasi')
                    ->action(function (Tagihan $record) {
                        $record->status = 'Lunas';
                        $record->tanggal_bayar = now();
                        $record->save();
                        Notification::make()->title('Pembayaran dikonfirmasi!')->success()->send();
                    }),
            ])
            ->bulkActions([]);
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['Belum Bayar', 'Butuh Konfirmasi'])->count();
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagihans::route('/'),
        ];
    }    
}
