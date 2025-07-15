<?php

namespace App\Filament\User\Resources\KosSayaResource\Pages;

use App\Filament\User\Resources\KosSayaResource;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Properti;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Carbon;

class BrowseProperti extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = KosSayaResource::class;
    protected static string $view = 'filament.user.resources.kos-saya-resource.pages.browse-properti';
    protected static ?string $title = 'Cari & Daftar Kos';

    public function table(Table $table): Table
    {
        return $table
            ->query(Properti::query()->whereHas('kamars', fn ($query) => $query->where('status_kamar', 'Kosong')))
            ->columns([
                TextColumn::make('nama_properti')->label('Nama Properti')->searchable(),
                TextColumn::make('alamat_properti')->label('Alamat')->limit(50)->html(),
                TextColumn::make('jenis')->label('Jenis Kos')->badge(),
                TextColumn::make('user.name')->label('Pemilik'),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('Daftar di Kos Ini')
                    ->icon('heroicon-o-pencil-square')
                    ->form(fn (Properti $record): array => $this->getRegistrationFormSchema($record))
                    ->action(fn (array $data, Properti $record) => $this->createPengajuan($data, $record))
                    ->modalWidth('4xl'),
            ]);
    }

    protected function getRegistrationFormSchema(Properti $properti): array
    {
        // Definisi formulir pendaftaran sudah benar, tidak perlu diubah.
        return [
            Section::make('Form Pendaftaran Penyewa Baru')->schema([
                Tabs::make('Pendaftaran')->tabs([
                    Tabs\Tab::make('Informasi Pribadi')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_penghuni')->label('Nama Lengkap')->default(Auth::user()->name)->required(),
                            TextInput::make('email_penghuni')->label('Email')->email()->default(Auth::user()->email)->readOnly()->required(),
                            TextInput::make('no_hp_penghuni')->label('No. Telepon/WA')->tel()->required(),
                            Select::make('jenis_kelamin_penghuni')->label('Jenis Kelamin')->options(['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'])->required(),
                            TextInput::make('pekerjaan_penghuni')->label('Pekerjaan/Status')->required(),
                            TextInput::make('alamat_penghuni')->label('Alamat Asal (sesuai KTP)')->required(),
                            FileUpload::make('foto_ktp_penghuni')->label('Foto KTP')->disk('public')->directory('foto-ktp')->image()->columnSpanFull()->required(),
                        ]),
                    ]),
                    Tabs\Tab::make('Kontak Darurat')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_kontak_darurat_penghuni')->label('Nama Kontak Darurat')->required(),
                            TextInput::make('no_hp_kontak_darurat_penghuni')->label('No. Telepon Darurat')->tel()->required(),
                        ]),
                    ]),
                    Tabs\Tab::make('Informasi Sewa')->schema([
                        Grid::make(2)->schema([
                            Select::make('kamar_id')->label('Pilih Kamar')
                                ->options(Kamar::where('properti_id', $properti->id)->where('status_kamar', 'Kosong')->pluck('nama_kamar', 'id'))
                                ->searchable()->required()->live(),
                            DatePicker::make('mulai_sewa')->label('Rencana Tanggal Masuk')->native(false)->required()->live(),
                            TextInput::make('durasi_angka')->label('Durasi Sewa')->numeric()->required()->live(onBlur: true),
                            Select::make('durasi_unit')->label('Satuan')
                                ->options(['day' => 'Hari', 'week' => 'Minggu', 'month' => 'Bulan', 'year' => 'Tahun'])
                                ->required()->live()
                                ->afterStateUpdated(function ($state, Get $get, Set $set) use ($properti) {
                                    $durasiAngka = $get('durasi_angka');
                                    $mulaiSewa = $get('mulai_sewa');
                                    $kamarId = $get('kamar_id');
                                    if (empty($durasiAngka) || empty($state) || empty($mulaiSewa) || empty($kamarId)) {
                                        $set('total_tagihan', 0); return;
                                    }
                                    $kamar = Kamar::find($kamarId);
                                    if (!$kamar) { $set('total_tagihan', 0); return; }
                                    $tipeKamar = $kamar->tipe_kamar;
                                    $hargaSewaData = $properti->harga_sewa ?? [];
                                    $hargaUnit = 0;
                                    $hargaKey = 'harga_' . match($state) {
                                        'day' => 'harian', 'week' => 'mingguan', 'month' => 'bulanan', 'year' => 'tahunan', default => ''
                                    };
                                    foreach ($hargaSewaData as $harga) {
                                        if (isset($harga['tipe']) && $harga['tipe'] === $tipeKamar && isset($harga[$hargaKey])) {
                                            $hargaUnit = $harga[$hargaKey]; break;
                                        }
                                    }
                                    $totalTagihan = (float)$hargaUnit * (int)$durasiAngka;
                                    $set('total_tagihan', $totalTagihan);
                                    $jatuhTempo = Carbon::parse($mulaiSewa)->{'add'.ucfirst($state).'s'}((int)$durasiAngka)->subDay()->format('Y-m-d');
                                    $set('jatuh_tempo', $jatuhTempo);
                                }),
                            TextInput::make('total_tagihan')->numeric()->prefix('Rp')->readOnly()->dehydrated(),
                            DatePicker::make('jatuh_tempo')->readOnly()->dehydrated(),
                        ]),
                    ]),
                ]),
            ]),
        ];
    }

    /**
     * ✅ --- INI ADALAH FUNGSI YANG DIPERBAIKI SECARA TOTAL --- ✅
     * Logika ini sekarang hanya membuat pengajuan, tidak lebih.
     */
    protected function createPengajuan(array $data, Properti $properti): void
    {
        // Gabungkan durasi sewa untuk deskripsi
        $durasi_sewa = ($data['durasi_angka'] ?? '') . ' ' . match($data['durasi_unit']) {
            'day' => 'Hari', 'week' => 'Minggu', 'month' => 'Bulan', 'year' => 'Tahun', default => ''
        };

        // Membuat data pengajuan di tabel 'penghunis'
        Penghuni::create([
            'properti_id' => $properti->id,
            'kamar_id' => $data['kamar_id'],
            
            // =====================================================================
            // ✅ Perubahan Kunci: Status diatur menjadi 'Pengajuan'
            // =====================================================================
            'status_penghuni' => 'Pengajuan', 

            // Data dari Tab Informasi Pribadi
            'nama_penghuni' => $data['nama_penghuni'],
            'email_penghuni' => $data['email_penghuni'],
            'no_hp_penghuni' => $data['no_hp_penghuni'],
            'jenis_kelamin_penghuni' => $data['jenis_kelamin_penghuni'],
            'pekerjaan_penghuni' => $data['pekerjaan_penghuni'],
            'foto_ktp_penghuni' => $data['foto_ktp_penghuni'],
            'alamat_penghuni' => $data['alamat_penghuni'],

            // Data dari Tab Kontak Darurat
            'nama_kontak_darurat_penghuni' => $data['nama_kontak_darurat_penghuni'],
            'no_hp_kontak_darurat_penghuni' => $data['no_hp_kontak_darurat_penghuni'],
            
            // Data dari Tab Informasi Sewa
            'mulai_sewa' => $data['mulai_sewa'],
            'durasi_sewa' => trim($durasi_sewa),
            'total_tagihan' => $data['total_tagihan'],
            'jatuh_tempo' => $data['jatuh_tempo'],
        ]);

        // 🛑 Logika pembuatan tagihan dan update kamar DIHAPUS dari sini
        // karena akan ditangani oleh admin saat konfirmasi.

        Notification::make()
            ->title('Pendaftaran Berhasil Diajukan!')
            ->body('Data Anda telah dikirim ke pemilik kos. Mohon tunggu konfirmasi selanjutnya.')
            ->success()
            ->send();
            
        // Arahkan pengguna ke halaman "Kos Saya" setelah berhasil
        redirect()->to(KosSayaResource::getUrl('index'));
    }
}
