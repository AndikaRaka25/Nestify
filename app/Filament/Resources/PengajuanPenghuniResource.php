<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanPenghuniResource\Pages;
use App\Models\Penghuni;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Notifications\Notification;

class PengajuanPenghuniResource extends Resource
{
    protected static ?string $model = Penghuni::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $label = 'Pengajuan';
    protected static ?string $pluralLabel = 'Pengajuan';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        // Hanya mengambil data yang statusnya pengajuan
        return parent::getEloquentQuery()->whereIn('status_penghuni', ['Pengajuan', 'Pengajuan Berhenti']);
    }

    public static function form(Form $form): Form
    {
        // Form ini hanya untuk halaman View (Lihat Detail)
        return $form
            ->schema([
                Section::make('Informasi Pengajuan')->schema([
                    Grid::make(2)->schema([
                        Placeholder::make('status_penghuni')->label('Jenis Pengajuan'),
                        Placeholder::make('nama_penghuni')->label('Nama Pemohon'),
                        Placeholder::make('properti.nama_properti')->label('Properti'),
                        Placeholder::make('kamar.nama_kamar')->label('Kamar'),
                    ]),
                ]),
                Section::make('Detail Pendaftar Baru')->visible(fn ($record) => $record?->status_penghuni === 'Pengajuan')->schema([
                    Grid::make(2)->schema([
                        Placeholder::make('email_penghuni')->label('Email'),
                        Placeholder::make('no_hp_penghuni')->label('No. Telepon'),
                        Placeholder::make('mulai_sewa')->label('Rencana Masuk')->content(fn($record) => $record->mulai_sewa ? \Carbon\Carbon::parse($record->mulai_sewa)->format('d F Y') : '-'),
                    ]),
                    FileUpload::make('foto_ktp_penghuni')->label('Foto KTP')->disabled()->disk('public'),
                ]),
                Section::make('Detail Pengajuan Berhenti')->visible(fn ($record) => $record?->status_penghuni === 'Pengajuan Berhenti')->schema([
                     Placeholder::make('rencana_tanggal_keluar')->label('Rencana Tanggal Keluar')->content(fn($record) => $record->rencana_tanggal_keluar ? \Carbon\Carbon::parse($record->rencana_tanggal_keluar)->format('d F Y') : '-'),
                     Placeholder::make('alasan_berhenti')->label('Alasan Berhenti')->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_penghuni')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('properti.nama_properti')->label('Properti'),
                Tables\Columns\BadgeColumn::make('status_penghuni')
                    ->label('Jenis Pengajuan')
                    ->colors(['primary' => 'Pengajuan', 'danger' => 'Pengajuan Berhenti']),
            ])
            ->filters([
                //
            ])
            ->actions([
                // ✅ --- SEMUA TOMBOL AKSI SEKARANG DIDEFINISIKAN DI SINI --- ✅
                Action::make('konfirmasi_masuk')
                    ->label('Konfirmasi Masuk')
                    ->icon('heroicon-o-check-circle')->color('success')->requiresConfirmation()
                    ->visible(fn (Penghuni $record): bool => $record->status_penghuni === 'Pengajuan')
                    ->action(function (Penghuni $record) {
                        $record->update(['status_penghuni' => 'Aktif']);
                        Tagihan::create([
                            'penghuni_id' => $record->id,
                            'properti_id' => $record->properti_id,
                            'kamar_id' => $record->kamar_id,
                            'invoice_number' => 'INV/' . now()->year . '/' . uniqid(),
                            'periode' => 'Tagihan Pertama - ' . $record->durasi_sewa,
                            'total_tagihan' => $record->total_tagihan,
                            'jatuh_tempo' => $record->jatuh_tempo,
                            'status' => 'Belum Bayar',
                        ]);
                        if ($record->kamar) $record->kamar->update(['status_kamar' => 'Aktif']);
                        Notification::make()->title('Penghuni Dikonfirmasi!')->success()->send();
                    }),
                
               Action::make('konfirmasi_berhenti')
                ->label('Konfirmasi Berhenti')
                ->icon('heroicon-o-archive-box-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pemberhentian Penghuni')
                ->modalDescription('Apakah Anda yakin? Riwayat penghuni akan dipertahankan, tetapi mereka akan dikeluarkan dari kamar.')
                ->visible(fn (Penghuni $record): bool => $record->status_penghuni === 'Pengajuan Berhenti')
                ->action(function (Penghuni $record) {
                    // 1. Simpan relasi kamar sebelum diubah
                    $kamar = $record->kamar;

                    // 2. Update status & putuskan hubungan dengan kamar
                    $record->update([
                        'status_penghuni' => 'Tidak Aktif',
                        'kamar_id' => null, // Ini adalah kunci untuk mencegah duplikasi di masa depan
                    ]);
                    
                    // 3. Update status kamar menjadi 'Kosong'
                    if ($kamar) {
                        $kamar->update(['status_kamar' => 'Kosong']);
                    }
                    
                    Notification::make()->title('Penghuni Telah Diberhentikan!')->success()->send();
                }),
            

                ViewAction::make()->label('Lihat Detail'),
            ]);
    }
    public static function getNavigationBadge(): ?string
    {
        // Menghitung jumlah pengajuan yang belum diproses
        return static::getModel()::whereIn('status_penghuni', ['Pengajuan', 'Pengajuan Berhenti'])->count();
        
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanPenghunis::route('/'),
            // Kita akan menggunakan ViewAction bawaan, tidak perlu halaman view kustom
        ];
    }
}
