<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\KosSayaResource\Pages;
use App\Models\Properti;
use App\Models\Penghuni;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Carbon;
use Filament\Actions\Action as ModalAction;
use Filament\Tables\Columns\ImageColumn;

class KosSayaResource extends Resource
{
    protected static ?string $model = Properti::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $label = 'Kos Saya';
    protected static ?string $pluralLabel = 'Kos Saya';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // Mendapatkan data penghuni yang relevan (aktif atau mengajukan berhenti)
        $penghuni = Penghuni::where('email_penghuni', Auth::user()->email)
                            ->whereIn('status_penghuni', ['Aktif', 'Pengajuan Berhenti'])
                            ->first();

        return $form
            ->schema([
                Section::make('Foto Properti')->collapsible()->schema([
                    Forms\Components\FileUpload::make('foto')->label(false)->multiple()->disabled()->disk('public'),
                ]),
                Section::make('Informasi Utama Properti')->schema([
                    Grid::make(2)->schema([
                        Placeholder::make('nama_properti')->label('Nama Properti')->content(fn ($record) => $record->nama_properti),
                        Placeholder::make('jenis_properti')->label('Jenis Kos')->content(fn ($record) => ucfirst($record->jenis)),
                    ]),
                    Placeholder::make('alamat_properti')->label('Alamat Lengkap')->content(fn ($record) => new \Illuminate\Support\HtmlString($record->alamat_properti)),
                ]),
                Section::make('Detail Sewa Anda')->schema([
                    Grid::make(2)->schema([
                        Placeholder::make('kamar_ditempati')->label('Kamar')->content($penghuni?->kamar?->nama_kamar ?? 'N/A'),
                        Placeholder::make('tipe_kamar')->label('Tipe Kamar')->content($penghuni?->kamar?->tipe_kamar ?? 'N/A'),
                        Placeholder::make('mulai_sewa')->label('Mulai Sewa')->content($penghuni?->mulai_sewa ? Carbon::parse($penghuni->mulai_sewa)->format('d F Y') : 'N/A'),
                        Placeholder::make('durasi_sewa')->label('Durasi Sewa')->content($penghuni?->durasi_sewa ?? 'N/A'),
                    ]),
                ]),
                Section::make('Informasi Pemilik')->collapsible()->schema([
                    Grid::make(2)->schema([
                        Placeholder::make('nama_pemilik')->label('Nama Pemilik')->content(fn ($record) => $record->user?->name ?? 'N/A'),
                        Placeholder::make('email_pemilik')->label('Email Pemilik')->content(fn ($record) => $record->user?->email ?? 'N/A'),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto.0') // Ambil foto pertama dari array
                    ->label('Foto')
                    ->disk('public')
                    ->width(100)
                    ->height(100)
                    ->circular(), 
                Tables\Columns\TextColumn::make('nama_properti')->label('Nama Properti'),
                Tables\Columns\TextColumn::make('alamat_properti')->label('Alamat')->limit(50)->html(),
                
                
                BadgeColumn::make('status_penyewaan')
                    ->label('Status Anda')
                    ->getStateUsing(function (Properti $record) {
                        
                        return $record->penghuni()
                                      ->where('email_penghuni', Auth::user()->email)
                                      ->whereIn('status_penghuni', ['Aktif', 'Pengajuan Berhenti'])
                                      ->first()?->status_penghuni;
                    })
                    ->colors(['primary' => 'Aktif', 'warning' => 'Pengajuan Berhenti']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),
                Action::make('ajukanBerhenti')
                    ->label('Ajukan Berhenti')
                    ->color('danger')->icon('heroicon-o-arrow-left-on-rectangle')->modalWidth('xl')
                    ->visible(function (Properti $record): bool {
                        // Tombol hanya muncul jika status untuk kos INI adalah 'Aktif'
                        $penghuni = $record->penghuni()->where('email_penghuni', Auth::user()->email)->where('status_penghuni', 'Aktif')->first();
                        return (bool)$penghuni;
                    })
                    ->form(function (Properti $record): array {
                        $penghuni = $record->penghuni()->where('email_penghuni', Auth::user()->email)->first();
                        if (!$penghuni) return [];

                        $hasUnpaidBills = Tagihan::where('penghuni_id', $penghuni->id)
                                                 ->whereIn('status', ['Belum Bayar', 'Butuh Konfirmasi'])
                                                 ->exists();
                        
                        if ($hasUnpaidBills) {
                            return [
                                Placeholder::make('peringatan_tunggakan')
                                    ->label('Pengajuan Belum Bisa Dilakukan')
                                    ->content('Anda masih memiliki tagihan yang belum lunas atau sedang menunggu konfirmasi.'),
                            ];
                        } else {
                            return [
                                Radio::make('alasan_berhenti_pilihan')->label('Pilih Alasan Berhenti')->options(['Pindah lokasi kerja/studi' => 'Pindah lokasi kerja/studi','Kenyamanan lingkungan' => 'Kenyamanan lingkungan','Kualitas fasilitas' => 'Kualitas fasilitas','Kualitas pelayanan' => 'Kualitas pelayanan','Lainnya' => 'Lainnya',])->required()->live(),
                                Textarea::make('alasan_berhenti_lainnya')->label('Sebutkan Alasan Lainnya')->visible(fn (Get $get): bool => $get('alasan_berhenti_pilihan') === 'Lainnya')->required(fn (Get $get): bool => $get('alasan_berhenti_pilihan') === 'Lainnya'),
                                DatePicker::make('rencana_tanggal_keluar')->label('Rencana Tanggal Keluar')->required()->native(false),
                            ];
                        }
                    })
                    ->action(function (Properti $record, array $data) {
                        if (!isset($data['alasan_berhenti_pilihan'])) return;

                        $alasanFinal = $data['alasan_berhenti_pilihan'];
                        if ($alasanFinal === 'Lainnya') $alasanFinal = $data['alasan_berhenti_lainnya'];
                        
                        $penghuni = $record->penghuni()->where('email_penghuni', Auth::user()->email)->where('status_penghuni', 'Aktif')->first();
                        if ($penghuni) {
                            $penghuni->update(['status_penghuni' => 'Pengajuan Berhenti', 'alasan_berhenti' => $alasanFinal, 'rencana_tanggal_keluar' => $data['rencana_tanggal_keluar']]);
                            Notification::make()->title('Pengajuan Berhenti Terkirim!')->success()->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKosSayas::route('/'),
            'browse-properti' => Pages\BrowseProperti::route('/browse'), 
            'view' => Pages\ViewKosSaya::route('/{record}/view'), 
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }

    
    public static function getEloquentQuery(): Builder
    {
        
        $penghuni = Penghuni::where('email_penghuni', Auth::user()->email)
                            ->whereIn('status_penghuni', ['Aktif', 'Pengajuan Berhenti'])
                            ->first();

        
        if (!$penghuni) {
            return parent::getEloquentQuery()->whereNull('id');
        }

        return parent::getEloquentQuery()->where('id', $penghuni->properti_id);
    }
}
