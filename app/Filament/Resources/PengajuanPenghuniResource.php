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
use App\Models\Kamar;
use Filament\Forms\Components\Select;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PengajuanPenghuniResource extends Resource
{
    protected static ?string $model = Penghuni::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $label = 'Pengajuan Penghuni';
    protected static ?string $pluralLabel = 'Pengajuan Penghuni';
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
                
                Action::make('konfirmasi_masuk')
                ->label('Konfirmasi Masuk')
                ->icon('heroicon-o-check-circle')->color('success')
                ->visible(fn (Penghuni $record): bool => $record->status_penghuni === 'Pengajuan')
                ->form([
                    
                    Select::make('kamar_id')
                        ->label('Pilih Kamar Untuk Ditempati')
                        ->options(function (Penghuni $record) {
                            
                            return Kamar::where('properti_id', $record->properti_id)
                                        ->where('status_kamar', 'Kosong')
                                        ->pluck('nama_kamar', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->placeholder('Pilih kamar yang tersedia')
                        ->helperText('Pilih kamar yang akan ditempati oleh penyewa ini.')
                        
                        ->visible(fn (Penghuni $record) => is_null($record->kamar_id)),
                ])
                ->requiresConfirmation()
                ->action(function (Penghuni $record, array $data) {
                    // Ambil kamar_id, utamakan dari form. Jika tidak ada, ambil dari record asli.
                    $kamarIdTerpilih = $data['kamar_id'] ?? $record->kamar_id;

                    // Validasi akhir untuk memastikan kamar_id tidak kosong
                    if (!$kamarIdTerpilih) {
                        Notification::make()->title('Aksi Gagal!')->body('Kamar untuk penyewa belum ditentukan.')->danger()->send();
                        return;
                    }
                    $totalTagihanAwal = (float) $record->total_tagihan;
                        $biayaTambahan = $record->properti->biaya_tambahan ?? [];
                        $totalBiayaTambahan = 0;
                        if (is_array($biayaTambahan)) {
                            foreach ($biayaTambahan as $biaya) {
                                $totalBiayaTambahan += (float) ($biaya['total_biaya'] ?? 0);
                            }
                        }

                        $totalTagihanAkhir = $totalTagihanAwal + $totalBiayaTambahan;

                    $record->update([
                        'status_penghuni' => 'Aktif',
                        'kamar_id' => $kamarIdTerpilih
                    ]);

                    Kamar::find($kamarIdTerpilih)->update(['status_kamar' => 'Aktif']);
                   $parts = explode(' ', $record->durasi_sewa);
                        $durasiAngka = (int) ($parts[0] ?? 1);
                        $durasiUnitText = $parts[1] ?? 'Bulan';
                        $durasiUnit = strtolower(Str::of($durasiUnitText)->singular());
                        
                        $tanggalMulai = Carbon::parse($record->mulai_sewa);

                        
                        $jatuhTempoPertama = $tanggalMulai->copy(); // Salin tanggal mulai
                        match ($durasiUnit) {
                            'hari'   => $jatuhTempoPertama->addDays(1),
                            'minggu'  => $jatuhTempoPertama->addWeeks(1),
                            'bulan' => $jatuhTempoPertama->addMonths(1),
                            'tahun'  => $jatuhTempoPertama->addYears(1),
                        };

                    
                    Tagihan::create([
                        'penghuni_id' => $record->id,
                        'properti_id' => $record->properti_id,
                        'kamar_id' => $kamarIdTerpilih,
                        'invoice_number' => 'INV/' . now()->year . '/' . uniqid(),
                         'periode' => 'Tagihan ke-1 dari ' . $durasiAngka . ' ' . ucfirst($durasiUnit),
                        'total_tagihan' => $totalTagihanAkhir,
                        'jatuh_tempo' => $jatuhTempoPertama,
                        'status' => 'Belum Bayar',
                    ]);
                    
                    Notification::make()->title('Penghuni Dikonfirmasi!')->body('Penyewa telah diaktifkan dan tagihan pertama dibuat.')->success()->send();
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
                    $kamar = $record->kamar;

                    $record->update([
                        'status_penghuni' => 'Tidak Aktif',
                        'kamar_id' => null, // Ini adalah kunci untuk mencegah duplikasi di masa depan
                    ]);
                    
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
        ];
    }
}
