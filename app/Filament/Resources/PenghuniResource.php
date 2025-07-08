<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Properti;
use Filament\Forms\Form;
use Filament\Forms\Get; 
use Filament\Forms\Set; 
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Columns\Column;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PenghuniResource\Pages;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenghuniResource\RelationManagers;

class PenghuniResource extends Resource
{
    protected static ?string $model = Penghuni::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationBadgeTooltip = 'Jumlah Penghuni';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    // Kolom Kiri (Detail Penghuni)
                    Forms\Components\Grid::make()
                        ->columnSpan(2)
                        ->columns(1)
                        ->schema([
                            Forms\Components\Section::make('Detail Penghuni')
                                ->schema([
                                    Tabs::make('TabsPenghuni')
                                        ->tabs([
                                            // Tab Biodata
                                            Tabs\Tab::make('Biodata')
                                                ->schema([
                                                    // ... field biodata lainnya ...
                                                    TextInput::make('nama_penghuni')->required(),
                                                    TextInput::make('alamat_penghuni')->required(),
                                                    TextInput::make('pekerjaan_penghuni')->required(),
                                                    Select::make('jenis_kelamin_penghuni') // ... options
                                                        ->required()
                                                        ->options([
                                                            'Laki-laki' => 'Laki-laki',
                                                            'Perempuan' => 'Perempuan',
                                                        ])
                                                        ->placeholder('Pilih Jenis Kelamin'),
    
                                                ])->columns(1),// Atur layout kolom jika perlu
    
                                            // Tab Lokasi & Sewa (Gabung atau pisah sesuai selera)
                                            Tabs\Tab::make('Lokasi & Sewa')
                                                ->schema([
                                                    // PILIH PROPERTI
                                                    Select::make('properti_id')
                                                        ->label('Properti')
                                                        ->options(Properti::query()->pluck('nama_properti', 'id'))
                                                        ->searchable()
                                                        ->required()
                                                        ->reactive() // <-- WAJIB: Agar field lain merespon perubahan ini
                                                        ->afterStateUpdated(fn (Set $set) => $set('kamar_id', null)), // <-- Reset pilihan kamar jika properti berubah
    
                                                    // PILIH KAMAR (Bergantung pada Properti)
                                                    Select::make('kamar_id')
                                                        ->label('Kamar')
                                                        ->options(function (Get $get) { // <-- Fungsi untuk load options dinamis
                                                            $propertiId = $get('properti_id');
                                                            if ($propertiId) {
                                                                return Kamar::query()
                                                                    ->where('properti_id', $propertiId)
                                                                    ->pluck('nama_kamar', 'id');
                                                            }
                                                          
                                                            return [];
                                                        })
                                                        ->searchable()
                                                        ->required()
                                                        ->reactive() // <-- WAJIB: Agar field lain merespon perubahan ini
                                                        ->placeholder('Pilih Kamar')
                                                        ->helperText('Pilih Kamar yang sesuai dengan Properti yang dipilih')
                                                        ->disabled(fn (Get $get): bool => ! $get('properti_id'))
                                                        ->live(), // <-- Bisa ditambahkan jika ingin interaksi lebih cepat
    
                                                   
                                                    Select::make('status_penghuni') 
                                                        ->required()
                                                        ->options([
                                                            'Pengajuan' => 'Pengajuan',
                                                            'Aktif' => 'Aktif',
                                                            'Tidak Aktif' => 'Tidak Aktif',
                                                        ])
                                                        ->default('Aktif')
                                                        ->reactive(),
                                                    TextInput::make('durasi_sewa'),
                                                    TextInput::make('total_tagihan')
                                                        ->label('Total Tagihan')
                                                        ->numeric()
                                                        ->prefix('Rp.') 
                                                        ->placeholder('0'),
                                                    DatePicker::make('mulai_sewa'),
                                                    DatePicker::make('jatuh_tempo'),
                                                ])
                                                ->columns(1), 
                                        ]),
                                ]),
                        ]),
    
                    // Kolom Kanan (Upload & Kontak)
                    Forms\Components\Grid::make()
                        ->columnSpan(1)
                        ->columns(1)
                        ->schema([
                             Forms\Components\Section::make('Foto KTP')
                                ->schema([
                                    FileUpload::make('foto_ktp_penghuni') 
                                        ->label('Upload Foto KTP')
                                        ->image()
                                        ->disk('public')
                                        ->directory('penghuni/ktp')                                      
                                        ->required()
                                        ->preserveFilenames() // <-- Menjaga nama file asli
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                ]),
                            Forms\Components\Section::make('Kontak')
                                ->schema([
                                    TextInput::make('no_hp_penghuni')->required(),
                                    TextInput::make('email_penghuni')->email()->required(),
                                    TextInput::make('nama_kontak_darurat_penghuni')->required(),
                                    TextInput::make('no_hp_kontak_darurat_penghuni')->required(),
                                    TextInput::make('hubungan_kontak_darurat_penghuni')->required(),
                                ]),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_penghuni')
                ->label('Nama Penghuni')
                ->searchable()
                ->sortable(),
                TextColumn::make('properti.nama_properti')
                ->label('Properti')
                ->searchable()
                ->sortable(),
                TextColumn::make('kamar.nama_kamar')
                ->label('Kamar')
                ->searchable()
                ->sortable(),
                TextColumn::make('alamat_penghuni')
                ->label('Alamat ')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jenis_kelamin_penghuni')
                ->label('Jenis Kelamin')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_hp_penghuni')->label('Nomor Telepon'),
                TextColumn::make('status_penghuni')
                ->badge()
                ->label('Status Penghuni')
                ->sortable()
                ->color(fn ($record): string => match ($record->status_penghuni ?? '') { 
                    'Pengajuan' => 'warning',
                    'Aktif' => 'success',
                    'Tidak Aktif' => 'danger',
                    default => 'gray', 
                })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('properti')
                ->relationship('properti', 'nama_properti')
                ->label('Filter Properti'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Penghuni $record): string => static::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Penghuni $record) {
                        $record->delete();
                        Notification::make()
                            ->title('Data Penghuni Berhasil Dihapus')
                            ->success()
                            ->send();
                    }),
                    
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
