<?php

namespace App\Filament\Resources;

use filament;
use components;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Tables;
use App\Models\Properti;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\PropertiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PropertiResource\RelationManagers;
use Symfony\Component\HttpKernel\Attribute\MapQueryString; 
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;
class PropertiResource extends Resource
{
    protected static ?string $model = Properti::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Properti';
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $label = 'Properti';
 
    public static function form(Form $form): Form
{
    return $form->schema([
            // Grid utama yang membagi layout menjadi 2 kolom (2/3 dan 1/3)
            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    // Kolom Kiri: Berisi semua input utama dengan Tabs
                    Forms\Components\Grid::make()
                        ->columnSpan(2)
                        ->columns(1)
                        ->schema([
                            Tabs::make('Properti Details')
                                ->tabs([
                                    // Tab 1: Detail Properti
                                    Tabs\Tab::make('Detail Properti')
                                        ->icon('heroicon-o-home')
                                        ->schema([
                                            Forms\Components\Section::make('Informasi Utama')
                                                ->schema([
                                                    TextInput::make('nama_properti')
                                                        ->required()->maxLength(255),
                                                    Select::make('jenis')
                                                        ->options([
                                                            'putra' => 'Putra',
                                                            'putri' => 'Putri',
                                                            'campur' => 'Campur',
                                                        ])->required(),
                                                ])->columns(2),
                                            Forms\Components\Section::make('Alamat Lengkap')
                                                ->schema([
                                                    RichEditor::make('alamat_properti')
                                                        ->required()
                                                        ->columnSpanFull(),
                                                ])->columns(3),
                                        ]),

                                    // Tab 2: Pengaturan Harga & Pembayaran
                                    Tabs\Tab::make('Harga & Pembayaran')
                                        ->icon('heroicon-o-currency-dollar')
                                        ->schema([
                                            Forms\Components\Section::make('Harga Sewa per Tipe Kamar')
                                                ->description('Atur berbagai pilihan harga sewa untuk setiap tipe kamar.')
                                                ->schema([
                                                    Repeater::make('harga_sewa')
                                                        ->schema([
                                                            Select::make('tipe')->options(['Tipe A' => 'Tipe A', 'Tipe B' => 'Tipe B', 'Tipe C' => 'Tipe C'])->required(),
                                                            TextInput::make('harga_harian')->numeric()->prefix('Rp'),
                                                            TextInput::make('harga_mingguan')->numeric()->prefix('Rp'),
                                                            TextInput::make('harga_bulanan')->numeric()->prefix('Rp'),
                                                            TextInput::make('harga_tahunan')->numeric()->prefix('Rp'),
                                                        ])->columns(2)->defaultItems(1)->addActionLabel('Tambah Tipe Harga'),
                                                ]),
                                            Forms\Components\Section::make('Biaya Tambahan Lainnya')
                                                ->schema([
                                                    Repeater::make('biaya_tambahan')
                                                        ->schema([
                                                            TextInput::make('nama_biaya')->required(),
                                                            TextInput::make('total_biaya')->numeric()->prefix('Rp')->required(),
                                                        ])->columns(2)->addActionLabel('Tambah Biaya'),
                                                ]),
                                            Forms\Components\Section::make('Informasi Pembayaran')
                                                ->schema([
                                                    Repeater::make('info_pembayaran')
                                                        ->schema([
                                                            TextInput::make('nama_bank')->label('Nama Bank / E-Wallet')->required(),
                                                            TextInput::make('nomor_rekening')->required(),
                                                            TextInput::make('nama_pemilik_rekening')->required(),
                                                        ])->columns(2)->addActionLabel('Tambah Metode Pembayaran'),
                                                ]),
                                                Forms\Components\Section::make('Diskon & Promo')
                                    ->description('Tambahkan kode promo yang bisa digunakan penyewa.')
                                    ->schema([
                                        Repeater::make('discounts')
                                            ->label('Daftar Diskon')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    TextInput::make('kode_promo')->label('Kode Promo')->required(),
                                                    TextInput::make('deskripsi_promo')->label('Deskripsi Singkat (misal: Hemat 10%)')->required(),
                                                ]),
                                                Grid::make(2)->schema([
                                                    ToggleButtons::make('jenis_diskon')
                                                        ->label('Jenis Diskon')
                                                        ->options([
                                                            'persen' => 'Persentase (%)',
                                                            'nominal' => 'Nominal (Rp)',
                                                        ])
                                                        ->required()
                                                        ->inline(),
                                                    TextInput::make('nilai_diskon')->label('Nilai Diskon')->numeric()->required(),
                                                ]),
                                            ])->addActionLabel('Tambah Diskon'),
                                        ]),
                                    ]),
                                ]),
                        ]),

                    // Kolom Kanan: Berisi upload foto dan peta
                    Forms\Components\Grid::make()
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\Section::make('Foto & Peta')
                                ->schema([
                                    FileUpload::make('foto')
                                        ->label('Foto Properti')
                                        ->multiple()->reorderable()->disk('public')->directory('kos_foto')
                                        ->image()->imageEditor()->maxFiles(5),
                                    
                                    // Placeholder untuk Map, bisa diintegrasikan nanti
                                    // Forms\Components\ViewField::make('map')
                                    //     ->view('filament.forms.components.map-picker'), 
                                    TextInput::make('latitude')->hidden(),
                                    TextInput::make('longitude')->hidden(),
                                  
                                ]),
                        ]),
                ]),
        ]);
    
}
    
    
public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function getRelations(): array
    {
        return [
            RelationManagers\KamarsRelationManager::class,
            RelationManagers\PenghuniRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertis::route('/'),
            'create' => Pages\CreateProperti::route('/create'),
            'edit' => Pages\EditProperti::route('/{record}/edit'),
            'view' => Pages\ViewProperti::route('/{record}/view'),
        ];
    }
    
    
}