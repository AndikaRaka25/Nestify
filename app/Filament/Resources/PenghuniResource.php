<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenghuniResource\Pages;
use App\Filament\Resources\PenghuniResource\RelationManagers;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Properti;
use Carbon\Carbon; 
use App\Models\User; 
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Tagihan;
use Illuminate\Support\Str;

class PenghuniResource extends Resource
{
    protected static ?string $model = Penghuni::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = 'Data Penghuni';
     protected static ?string $navigationBadgeTooltip = 'Jumlah Penghuni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()->columns(3)->schema([
                // Kolom Kiri
                Forms\Components\Grid::make()->columnSpan(2)->schema([
                    Forms\Components\Section::make('Detail Penghuni')->schema([
                        Tabs::make('TabsPenghuni')->tabs([
                            Tabs\Tab::make('Biodata')->icon('heroicon-o-user-circle')->schema([
                                Forms\Components\TextInput::make('nama_penghuni')->required()->maxLength(255),
                                Forms\Components\TextInput::make('alamat_penghuni')->required()->maxLength(255),
                                Forms\Components\TextInput::make('pekerjaan_penghuni')->required()->maxLength(255),
                                Forms\Components\Select::make('jenis_kelamin_penghuni')->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])->required(),
                            ])->columns(2),

                            // Tab Lokasi & Sewa dengan LOGIKA BARU
                            Tabs\Tab::make('Lokasi & Sewa')->icon('heroicon-o-map-pin')->schema([
                                Forms\Components\Select::make('properti_id')
                                    ->relationship('properti', 'nama_properti')->searchable()->preload()->required()->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('kamar_id', null);
                                        $set('total_tagihan', null);
                                        $set('jatuh_tempo', null);
                                    }),

                                Forms\Components\Select::make('kamar_id')
                                    ->label('Kamar')
                                    // PENYEMPURNAAN: Hanya menampilkan kamar yang kosong
                                    ->options(fn (Get $get): array => Kamar::query()->where('properti_id', $get('properti_id'))->where('status_kamar', 'Kosong')->pluck('nama_kamar', 'id')->all())
                                    ->searchable()->required()->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::hitungTagihanDanJatuhTempo($get, $set)),

                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('durasi_angka')
                                        ->label('Durasi Sewa')
                                        ->numeric()->required()->live(onBlur: true)
                                        ->afterStateUpdated(fn (Get $get, Set $set) => self::hitungTagihanDanJatuhTempo($get, $set)),
                                    Forms\Components\Select::make('durasi_unit')
                                        ->label('Satuan')
                                        ->options(['hari' => 'Hari', 'minggu' => 'Minggu', 'bulan' => 'Bulan', 'tahun' => 'Tahun'])
                                        ->required()->live()
                                        ->afterStateUpdated(fn (Get $get, Set $set) => self::hitungTagihanDanJatuhTempo($get, $set)),
                                ]),
                                
                                Forms\Components\DatePicker::make('mulai_sewa')
                                    ->required()->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::hitungTagihanDanJatuhTempo($get, $set)),
                                
                                Forms\Components\TextInput::make('total_tagihan')
                                ->label('Total Tagihan (Termasuk Biaya Tambahan)')
                                    ->numeric()->prefix('Rp')->readOnly()->dehydrated(),

                                Forms\Components\DatePicker::make('jatuh_tempo')
                                    ->readOnly()->dehydrated(),

                                Forms\Components\Select::make('status_penghuni')->options(['Pengajuan' => 'Pengajuan', 'Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif'])->default('Aktif')->required(),
                            ])->columns(2),
                        ]),
                    ]),
                ]),

                // Kolom Kanan
                Forms\Components\Grid::make()->columnSpan(1)->schema([
                    Forms\Components\Section::make('Dokumen & Kontak')->schema([
                        Forms\Components\FileUpload::make('foto_ktp_penghuni')->label('Upload Foto KTP')->image()->disk('public')->directory('penghuni/ktp')->required(),
                        Forms\Components\TextInput::make('no_hp_penghuni')->tel()->required(),
                        Forms\Components\TextInput::make('email_penghuni')->email()->required(),
                        Forms\Components\TextInput::make('nama_kontak_darurat_penghuni')->label('Nama Kontak Darurat')->required(),
                        Forms\Components\TextInput::make('no_hp_kontak_darurat_penghuni')->label('No. HP Kontak Darurat')->tel()->required(),
                    ]),
                ]),
            ]),
        ]);
    }

    public static function hitungTagihanDanJatuhTempo(Get $get, Set $set): void
    {
        $kamarId = $get('kamar_id');
        $durasiAngka = $get('durasi_angka');
        $durasiUnit = $get('durasi_unit');
        $mulaiSewa = $get('mulai_sewa');

        if (!$kamarId || !$durasiAngka || !$durasiUnit || !$mulaiSewa) {
            $set('total_tagihan', 0);
            return;
        }

        $kamar = Kamar::with('properti')->find($kamarId);
        if (!$kamar || !$kamar->properti) {
            $set('total_tagihan', 0);
            return;
        }

        // --- Menghitung Harga Sewa (Logika ini sudah benar) ---
        $hargaSewaData = $kamar->properti->harga_sewa ?? [];
        $hargaUnit = 0;
        $hargaKey = match ($durasiUnit) {
            'hari' => 'harga_harian', 'minggu' => 'harga_mingguan', 'bulan' => 'harga_bulanan', 'tahun' => 'harga_tahunan', default => ''
        };

        foreach ($hargaSewaData as $harga) {
            if (($harga['tipe'] ?? null) === $kamar->tipe_kamar && isset($harga[$hargaKey])) {
                $hargaUnit = $harga[$hargaKey];
                break;
            }
        }
        $totalHargaSewa = (float)$hargaUnit * (int)$durasiAngka;

        // --- Menghitung Biaya Tambahan (Logika BARU ditambahkan di sini) ---
        $biayaTambahanData = $kamar->properti->biaya_tambahan ?? [];
        $totalBiayaTambahan = 0;
        if (is_array($biayaTambahanData)) {
            foreach ($biayaTambahanData as $biaya) {
                $totalBiayaTambahan += (float)($biaya['total_biaya'] ?? 0);
            }
        }

        // --- Kalkulasi Total Tagihan Akhir ---
        $totalTagihanAkhir = $totalHargaSewa + $totalBiayaTambahan;
        $set('total_tagihan', $totalTagihanAkhir);

        // --- Menghitung Jatuh Tempo (Logika ini sudah benar) ---
        $tanggalMulai = Carbon::parse($mulaiSewa);
        $jatuhTempo = match ($durasiUnit) {
            'hari' => $tanggalMulai->addDays((int)$durasiAngka),
            'minggu' => $tanggalMulai->addWeeks((int)$durasiAngka),
            'bulan' => $tanggalMulai->addMonths((int)$durasiAngka),
            'tahun' => $tanggalMulai->addYears((int)$durasiAngka),
        };
        $set('jatuh_tempo', $jatuhTempo->subDay()->format('Y-m-d'));
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_penghuni')->searchable(),
                Tables\Columns\TextColumn::make('properti.nama_properti')->searchable(),
                Tables\Columns\TextColumn::make('kamar.nama_kamar')->searchable(),
                Tables\Columns\TextColumn::make('status_penghuni')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pengajuan' => 'warning',
                        'Aktif' => 'success',
                        'Tidak Aktif' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('properti_id')
                    ->relationship('properti', 'nama_properti')
                    ->label('Filter Properti'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('buatTagihan')
                    ->label('Buat Tagihan')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->requiresConfirmation()
                    // Tombol ini hanya akan muncul jika penghuni belum punya tagihan
                    ->visible(fn (Penghuni $record): bool => !$record->tagihan()->exists())
                    ->action(function (Penghuni $record) {
                        // Logika yang sama persis dengan yang ada di model
                        Tagihan::create([
                            'penghuni_id' => $record->id,
                            'properti_id' => $record->properti_id,
                            'kamar_id' => $record->kamar_id,
                            'invoice_number' => 'INV-' . now()->year . now()->month . '-' . Str::upper(Str::random(6)),
                            'periode' => now()->format('F Y'),
                            'total_tagihan' => $record->total_tagihan,
                            'jatuh_tempo' => $record->jatuh_tempo,
                            'status' => 'Belum Bayar',
                        ]);

                        Notification::make()
                            ->title('Tagihan berhasil dibuat!')
                            ->body("Tagihan untuk {$record->nama_penghuni} telah berhasil dibuat.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
  public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_penghuni', 'Aktif')->count();
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
            'index'  => Pages\ListPenghunis::route('/'),
            'create' => Pages\CreatePenghuni::route('/create'),
            'edit'   => Pages\EditPenghuni::route('/{record}/edit'),
            'view'   => Pages\ViewPenghuni::route('/{record}/view'),
        ];
    }
}
