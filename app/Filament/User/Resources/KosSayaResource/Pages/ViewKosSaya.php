<?php

namespace App\Filament\User\Resources\KosSayaResource\Pages;

use App\Filament\User\Resources\KosSayaResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class ViewKosSaya extends ViewRecord
{
    protected static string $resource = KosSayaResource::class;

    // Kita tidak lagi mendefinisikan view blade, karena semua dibuat di sini.
    // protected static string $view = '...'; // HAPUS BARIS INI

    // Mengubah judul halaman agar dinamis sesuai nama properti
    public function getTitle(): string
    {
        return "Detail: " . $this->record->nama_properti;
    }

    // ✅ --- INI ADALAH BAGIAN UTAMA YANG MEMBANGUN TAMPILAN --- ✅
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian untuk menampilkan galeri foto
                Section::make('Galeri Foto')
                    ->schema([
                        FileUpload::make('foto')
                            ->label('')
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('kos_foto')
                            ->disabled() // Mode read-only
                            ->hiddenLabel(),
                    ])->collapsible(),

                // Bagian untuk detail utama properti
                Section::make('Informasi Utama')
                    ->schema([
                        Grid::make(2)->schema([
                            Placeholder::make('nama_properti_ph')
                                ->label('Nama Properti')
                                ->content($this->record->nama_properti),
                            Placeholder::make('jenis_ph')
                                ->label('Jenis Kos')
                                ->content(ucfirst($this->record->jenis)),
                        ]),
                        // Menggunakan RichEditor disabled untuk merender HTML dari alamat
                        RichEditor::make('alamat_properti')
                            ->label('Alamat Lengkap')
                            ->disabled()
                            ->toolbarButtons([]), // Sembunyikan toolbar
                    ]),

                // Bagian untuk harga dan biaya dalam 2 kolom
                Grid::make(2)->schema([
                    Section::make('Harga Sewa')
                        ->schema([
                            Repeater::make('harga_sewa')
                                ->label('')
                                ->schema([
                                    TextInput::make('tipe')->readOnly(),
                                    TextInput::make('harga_harian')->numeric()->prefix('Rp')->readOnly(),
                                    TextInput::make('harga_mingguan')->numeric()->prefix('Rp')->readOnly(),
                                    TextInput::make('harga_bulanan')->numeric()->prefix('Rp')->readOnly(),
                                    TextInput::make('harga_tahunan')->numeric()->prefix('Rp')->readOnly(),
                                ])
                                ->columns(2)
                                ->disabled() // Seluruh repeater read-only
                                ->addable(false)
                                ->deletable(false),
                        ]),
                    Section::make('Biaya Tambahan')
                        ->schema([
                            Repeater::make('biaya_tambahan')
                                ->label('')
                                ->schema([
                                    TextInput::make('nama_biaya')->readOnly(),
                                    TextInput::make('total_biaya')->numeric()->prefix('Rp')->readOnly(),
                                ])
                                ->columns(2)
                                ->disabled()
                                ->addable(false)
                                ->deletable(false),
                        ]),
                ]),
            ]);
    }

    // Menambahkan tombol "Daftar di Kos Ini" di header halaman detail
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Daftar di Kos Ini')
                ->color('primary')
                ->icon('heroicon-o-pencil-square')
                // Arahkan ke halaman browse properti, karena modal pendaftaran ada di sana
                ->url(KosSayaResource::getUrl('browse-properti')),
        ];
    }
}
