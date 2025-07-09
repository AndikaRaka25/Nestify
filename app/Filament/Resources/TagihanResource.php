<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $label = 'Manajemen Tagihan';

    public static function form(Form $form): Form
    {
        // Form hanya untuk melihat detail, jadi kita buat readonly
        return $form
            ->schema([
                Forms\Components\TextInput::make('penghuni.nama_penghuni')->label('Nama Penghuni')->disabled(),
                Forms\Components\TextInput::make('properti.nama_properti')->label('Properti')->disabled(),
                Forms\Components\TextInput::make('kamar.nama_kamar')->label('Kamar')->disabled(),
                Forms\Components\TextInput::make('invoice_number')->label('Nomor Invoice')->disabled(),
                Forms\Components\TextInput::make('periode')->label('Periode')->disabled(),
                Forms\Components\TextInput::make('total_tagihan')->label('Total Tagihan')->money('IDR')->disabled(),
                Forms\Components\DatePicker::make('jatuh_tempo')->label('Jatuh Tempo')->disabled(),
                Forms\Components\Select::make('status')->options(['Belum Bayar' => 'Belum Bayar', 'Butuh Konfirmasi' => 'Butuh Konfirmasi', 'Lunas' => 'Lunas']),
                Forms\Components\FileUpload::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Invoice')->searchable(),
                Tables\Columns\TextColumn::make('penghuni.nama_penghuni')->label('Penghuni')->searchable(),
                Tables\Columns\TextColumn::make('periode')->sortable(),
                Tables\Columns\TextColumn::make('total_tagihan')->money('IDR')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Belum Bayar',
                        'info' => 'Butuh Konfirmasi',
                        'success' => 'Lunas',
                    ]),
                Tables\Columns\TextColumn::make('jatuh_tempo')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Lihat/Ubah Status'),
                Action::make('konfirmasi')
                    ->label('Konfirmasi Lunas')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    // Hanya tampilkan tombol ini jika statusnya 'Butuh Konfirmasi'
                    ->visible(fn (Tagihan $record): bool => $record->status === 'Butuh Konfirmasi')
                    ->action(function (Tagihan $record) {
                        $record->status = 'Lunas';
                        $record->tanggal_bayar = now();
                        $record->save();
                        Notification::make()->title('Pembayaran dikonfirmasi!')->success()->send();
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagihans::route('/'),
            // Kita sembunyikan halaman create dan edit karena manajemen dilakukan via tabel
            // 'create' => Pages\CreateTagihan::route('/create'),
            // 'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }    
}
