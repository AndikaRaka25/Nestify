<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\TagihanResource\Pages;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\Penghuni;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Illuminate\Support\HtmlString;
    

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Tagihan Saya';
  protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // Form ini akan digunakan untuk halaman "Lihat Detail"
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Tagihan')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Placeholder::make('invoice_number')
                                ->label('Nomor Invoice')
                                ->content(fn ($record) => $record->invoice_number),

                            Forms\Components\Placeholder::make('status')
                                ->label('Status Pembayaran')
                                ->content(function ($record) {
                                    // Membuat badge status yang sama seperti di tabel
                                    $status = $record->status;
                                    $color = match ($status) {
                                        'Belum Bayar' => 'danger',
                                        'Butuh Konfirmasi' => 'warning',
                                        'Lunas' => 'success',
                                        default => 'gray',
                                    };
                                    return new \Illuminate\Support\HtmlString(
                                        "<span class=\"fi-badge fi-color-{$color} text-xs font-medium ring-1 ring-inset px-2 min-w-[2.5rem] py-1 rounded-md\" style=\"--c-50:var(--{$color}-50);--c-400:var(--{$color}-400);--c-600:var(--{$color}-600);\">{$status}</span>"
                                    );
                                }),

                            Forms\Components\Placeholder::make('periode')
                                ->label('Periode Tagihan')
                                ->content(fn ($record) => $record->periode),

                            Forms\Components\Placeholder::make('diskon')
                                ->label('Diskon yang Digunakan')
                                ->content(function ($record) {
                                    // Cek jika ada data diskon yang tersimpan di dalam record tagihan
                                    if ($discount = $record->applied_discount) {
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
                    
                        

                            Forms\Components\Placeholder::make('jatuh_tempo')
                                ->label('Jatuh Tempo')
                                ->content(fn ($record) => $record->jatuh_tempo->format('d F Y')),

                            Forms\Components\Placeholder::make('total_tagihan')
                                ->label('Total Tagihan')
                                ->content(fn ($record) => 'Rp ' . number_format($record->total_tagihan, 0, ',', '.')),
                            
                            Forms\Components\Placeholder::make('tanggal_bayar')
                                ->label('Tanggal Bayar')
                                ->content(fn ($record) => $record->tanggal_bayar ? $record->tanggal_bayar->format('d F Y, H:i') : '-'),
                        ]),
                    ]),
                
                Forms\Components\Section::make('Informasi Penyewa & Properti')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Placeholder::make('nama_penghuni')
                                ->label('Ditagihkan Kepada')
                                ->content(fn ($record) => $record->penghuni->nama_penghuni ?? '-'),

                            Forms\Components\Placeholder::make('nama_properti')
                                ->label('Properti')
                                ->content(fn ($record) => $record->properti->nama_properti ?? '-'),

                            Forms\Components\Placeholder::make('nama_kamar')
                                ->label('Kamar')
                                ->content(fn ($record) => $record->kamar->nama_kamar ?? '-'),

                            Forms\Components\Placeholder::make('tipe_kamar')
                                ->label('Tipe Kamar')
                                ->content(fn ($record) => $record->kamar->tipe_kamar ?? '-'),
                        ]),
                    ]),
                
                Forms\Components\Section::make('Bukti Pembayaran')
                    ->visible(fn ($record) => !is_null($record->bukti_pembayaran)) // Hanya tampil jika ada bukti bayar
                    ->schema([
                        Forms\Components\FileUpload::make('bukti_pembayaran')
                            ->label('')
                            ->disabled()
                            ->disk('public'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('No. Invoice')->searchable(),
                Tables\Columns\TextColumn::make('periode')->label('Periode'),
                Tables\Columns\TextColumn::make('total_tagihan')->label('Total')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('jatuh_tempo')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Belum Bayar',
                        'warning' => 'Butuh Konfirmasi',
                        'success' => 'Lunas',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                // ✅ --- KODE BARU TANPA ACTION GROUP --- ✅
                // Tombol aksi akan ditampilkan berjajar secara individual.

                // Aksi untuk Upload Bukti Bayar
                 Tables\Actions\Action::make('bayar')
                    ->label('Bayar & Upload Bukti')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->modalWidth('2xl')
                    ->visible(fn (Tagihan $record): bool => $record->status === 'Belum Bayar')
                    ->form(function (Tagihan $record): array {
                        $properti = $record->properti;
                        $infoPembayaran = $properti?->info_pembayaran ?? [];
                        $discounts = $properti?->discounts ?? [];
                        
                        $discountOptions = ['' => 'Tanpa Promo'];
                        foreach ($discounts as $index => $discount) {
                            $discountOptions[$index] = "{$discount['kode_promo']} - {$discount['deskripsi_promo']}";
                        }

                        return [
                            View::make('filament.forms.components.info-pembayaran')
                                ->viewData(['infoPembayaran' => $infoPembayaran]),
                            
                            Placeholder::make('total_asli')
                                ->label('Total Tagihan Asli')
                                ->content('Rp ' . number_format($record->total_tagihan, 0, ',', '.')),
                            
                            Select::make('promo_pilihan')
                                ->label('Gunakan Promo')
                                ->options($discountOptions)
                                ->live(),

                            Placeholder::make('total_akhir')
                                ->label('Total yang Harus Dibayar')
                                ->content(function (Get $get) use ($record, $discounts): HtmlString {
                                    $totalAwal = $record->total_tagihan;
                                    $promoIndex = $get('promo_pilihan');

                                    if ($promoIndex !== null && $promoIndex !== '' && isset($discounts[$promoIndex])) {
                                        $promo = $discounts[$promoIndex];
                                        $nilaiDiskon = (float) $promo['nilai_diskon'];
                                        
                                        if ($promo['jenis_diskon'] === 'persen') {
                                            $totalAwal -= $totalAwal * ($nilaiDiskon / 100);
                                        } else {
                                            $totalAwal -= $nilaiDiskon;
                                        }
                                    }
                                    
                                    $formattedTotal = '<span class="text-xl font-bold text-primary-600">Rp ' . number_format(max(0, $totalAwal), 0, ',', '.') . '</span>';
                                    return new HtmlString($formattedTotal);
                                })
                                ->helperText('Total akan ter-update otomatis setelah Anda memilih promo.'),

                            Forms\Components\FileUpload::make('bukti_pembayaran')
                                ->label('Upload Bukti Pembayaran Anda')
                                ->disk('public')->directory('bukti-pembayaran')
                                ->image()->required(),
                        ];
                    })
                    // ✅ --- PERBAIKAN UTAMA DI SINI --- ✅
                    ->action(function (Tagihan $record, array $data) {
                        // 1. Hitung ulang total akhir berdasarkan promo yang dipilih
                        $totalAkhir = $record->total_tagihan;
                        $promoIndex = $data['promo_pilihan'];
                        $discounts = $record->properti?->discounts ?? [];

                        if ($promoIndex !== null && $promoIndex !== '' && isset($discounts[$promoIndex])) {
                            $promo = $discounts[$promoIndex];
                            $nilaiDiskon = (float) $promo['nilai_diskon'];
                            
                            if ($promo['jenis_diskon'] === 'persen') {
                                $totalAkhir -= $totalAkhir * ($nilaiDiskon / 100);
                            } else {
                                $totalAkhir -= $nilaiDiskon;
                            }
                        }

                        // 2. Simpan semua data yang benar ke database
                        $record->update([
                            'bukti_pembayaran' => $data['bukti_pembayaran'],
                            'total_tagihan' => max(0, $totalAkhir), // Simpan total tagihan yang baru
                            'status' => 'Butuh Konfirmasi',
                            'tanggal_bayar' => now(),
                        ]);
                        
                        Notification::make()
                            ->title('Bukti Pembayaran Berhasil Diupload!')
                            ->body('Mohon tunggu konfirmasi dari pemilik kos.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
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
            // Kita nonaktifkan halaman 'create' karena penyewa tidak membuat tagihan
            // 'create' => Pages\CreateTagihan::route('/create'), 
            'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }

    // !! BAGIAN PALING PENTING UNTUK KEAMANAN !!
    // Memastikan penyewa hanya bisa melihat tagihannya sendiri.
    public static function getEloquentQuery(): Builder
    {
        // 1. Dapatkan email pengguna yang sedang login
        $userEmail = Auth::user()->email;

        // 2. Cari SEMUA ID penghuni yang pernah terdaftar dengan email tersebut
        $penghuniIds = Penghuni::where('email_penghuni', $userEmail)->pluck('id')->toArray();

        // 3. Jika tidak ditemukan ID sama sekali, jangan tampilkan apa-apa
        if (empty($penghuniIds)) {
            return parent::getEloquentQuery()->whereNull('id');
        }

        // 4. Tampilkan semua tagihan yang penghuni_id-nya ada di dalam daftar ID tersebut
        return parent::getEloquentQuery()->whereIn('penghuni_id', $penghuniIds);
    }
}