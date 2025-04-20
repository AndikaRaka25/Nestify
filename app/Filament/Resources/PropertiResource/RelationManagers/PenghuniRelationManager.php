<?php

namespace App\Filament\Resources\PropertiResource\RelationManagers;
;

use Filament\Forms;
use Filament\Tables;
use App\Models\Penghuni;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Form;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\PenghuniResource;

class PenghuniRelationManager extends RelationManager
{
    protected static string $relationship = 'penghunis';
    protected static ?string $recordTitleAttribute = 'nama_penghuni';
    protected static ?string $label = 'Penghuni';
    protected static ?string $pluralLabel = 'Penghuni';
    protected static ?string $title = 'Data Penghuni';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    // Kolom kiri (mengambil 2 kolom dari 3)
                    Forms\Components\Grid::make()
                        ->columnSpan(2)
                        ->columns(1)
                        ->schema([
                            Forms\Components\Section::make('Silahkan Isi Detail berikut')
                                ->schema([
                                    Tabs::make('Detail Penghuni')
                                        ->tabs([
                                            // Tab Biodata
                                            Tabs\Tab::make('Biodata')
                                                ->schema([
                                                    TextInput::make('id')
                                                        ->label('ID')
                                                        ->disabled()
                                                        ->hidden(),
                                                    TextInput::make('nama_penghuni')
                                                        ->label('Nama Penghuni')
                                                        ->required(),
                                                    TextInput::make('alamat_penghuni')
                                                        ->label('Alamat Penghuni')
                                                        ->required(),
                                                    Select::make('jenis_kelamin_penghuni')
                                                        ->label('Jenis Kelamin')
                                                        ->options([
                                                            'Laki-Laki'      => 'Laki-Laki',
                                                            'Perempuan'      => 'Perempuan',
                                                            'Tidak Diketahui'=> 'Tidak Diketahui',
                                                        ])
                                                        ->required(),
                                                    TextInput::make('pekerjaan_penghuni')
                                                        ->label('Pekerjaan')
                                                        ->required(),
                                                    Select::make('status_penghuni')
                                                        ->label('Status Penghuni')
                                                        ->options([
                                                            'Aktif'       => 'Aktif',
                                                            'Tidak Aktif' => 'Tidak Aktif',
                                                            'Pengajuan'   => 'Pengajuan',
                                                        ])
                                                        ->required(),
                                                ])
                                                ->columns(1),
                                            // Tab Kontrak sewa
                                            Tabs\Tab::make('Kontrak sewa')
                                                ->schema([
                                                    TextInput::make('durasi_sewa')
                                                        ->label('Durasi Sewa'),
                                                    TextInput::make('total_tagihan')
                                                        ->label('Total Tagihan'),
                                                    DatePicker::make('mulai_sewa')
                                                        ->label('Mulai Sewa')
                                                        ->helperText('Format tanggal: Hari-Bulan-Tahun'),
                                                    DatePicker::make('jatuh_tempo')
                                                        ->label('Jatuh Tempo')
                                                        ->helperText('Format tanggal: YYYY-MM-DD'),
                                                ])
                                                ->columns(1),
                                        ]),
                                ]),
                        ]),
                    // Kolom kanan (mengambil 1 kolom dari 3)
                    Forms\Components\Grid::make()
                        ->columnSpan(1)
                        ->columns(1)
                        ->schema([
                            Forms\Components\Section::make('Silahkan Upload Foto KTP')
                                ->schema([
                                    FileUpload::make('foto_ktp_penghuni')
                                        ->label('Foto KTP')
                                        ->required()
                                        ->image()
                                        ->imageEditor()
                                        ->directory('foto_ktp_penghuni'),
                                ]),
                            Forms\Components\Section::make('Silahkan Isi Kontak')
                                ->schema([
                                    TextInput::make('no_hp_penghuni')
                                        ->label('Nomor Telepon Penghuni')
                                        ->required(),
                                    TextInput::make('email_penghuni')
                                        ->label('Email Penghuni')
                                        ->email()
                                        ->required(),
                                    TextInput::make('nama_kontak_darurat_penghuni')
                                        ->label('Nama Kontak Darurat')
                                        ->required(),
                                    TextInput::make('no_hp_kontak_darurat_penghuni')
                                        ->label('Nomor Telepon Kontak Darurat')
                                        ->required(),
                                    TextInput::make('hubungan_kontak_darurat_penghuni')
                                        ->label('Hubungan Kontak Darurat')
                                        ->required(),
                                ]),
                        ]),
                ]),
        ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_penghuni')
                ->label('Nama Penghuni')
                ->searchable()
                ->sortable(),
                TextColumn::make('alamat_penghuni')->label('Alamat '),
                TextColumn::make('jenis_kelamin_penghuni')
                ->label('Jenis Kelamin')
                ->sortable(),
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
                
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Tambah Penghuni'),
            ])

            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Penghuni $record): string => PenghuniResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(function (Penghuni $record) {
                        $record->delete();
                        Notification::make()
                            ->title('Data Penghuni Berhasil Dihapus')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                    
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
