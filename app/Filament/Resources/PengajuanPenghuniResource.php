<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanPenghuniResource\Pages;
use App\Models\PengajuanPenghuni;
use App\Models\Penghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class PengajuanPenghuniResource extends Resource
{
    protected static ?string $model = PengajuanPenghuni::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $label = 'Pengajuan Penghuni';
    protected static ?string $pluralLabel = 'Pengajuan Penghuni';


    public static function form(Form $form): Form
    {
        // Form tidak kita gunakan karena kita tidak membuat/mengedit dari sini
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_penghuni')
                    ->label('Nama Pemohon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('properti.nama_properti')
                    ->label('Properti yang Diajukan'),
                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar yang Diajukan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tombol Aksi Kustom: Terima
                Action::make('terima')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation() // Meminta konfirmasi sebelum eksekusi
                    ->action(function (Penghuni $record) {
                        // Ubah status penghuni menjadi 'Aktif'
                        $record->status_penghuni = 'Aktif';
                        $record->save();

                        // Ubah status kamar menjadi 'Aktif' (Terisi)
                        if ($record->kamar) {
                            $record->kamar->status_kamar = 'Aktif';
                            $record->kamar->keterangan_kamar = 'Terisi';
                            $record->kamar->save();
                        }
                        
                        Notification::make()
                            ->title('Pengajuan diterima')
                            ->body("Penghuni dengan nama {$record->nama_penghuni} telah berhasil diterima.")
                            ->success()
                            ->send();
                    }),

                // Tombol Aksi Kustom: Tolak
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Penghuni $record) {
                        // Hapus data pengajuan (penghuni)
                        $record->delete();
                        
                        Notification::make()
                            ->title('Pengajuan ditolak')
                            ->body("Pengajuan dari {$record->nama_penghuni} telah ditolak dan dihapus.")
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }
    
    // Menyembunyikan halaman create dan edit dari resource ini
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanPenghunis::route('/'),
        ];
    }

    // Memberi badge notifikasi untuk jumlah pengajuan baru
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
