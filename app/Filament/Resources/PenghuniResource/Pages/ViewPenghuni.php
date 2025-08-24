<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ViewPenghuni extends ViewRecord
{
    protected static string $resource = PenghuniResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->action(function () {
                    $this->record->delete();
                    Notification::make()
                        ->title('Data Penghuni Berhasil Dihapus')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation(),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Detail Penghuni')
                ->tabs([
                    // Tab Biodata
                    Tabs\Tab::make('Biodata')
                        ->schema([
                            TextInput::make('id')
                                ->label('ID')
                                ->disabled(),
                            TextInput::make('nama_penghuni')
                                ->label('Nama Penghuni'),
                            TextInput::make('alamat_penghuni')
                                ->label('Alamat Penghuni'),
                            Select::make('jenis_kelamin_penghuni')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'Laki-Laki' => 'Laki-Laki',
                                    'Perempuan' => 'Perempuan',
                                    'Tidak Diketahui' => 'Tidak Diketahui',
                                ]),
                            TextInput::make('pekerjaan_penghuni')
                                ->label('Pekerjaan'),
                            TextInput::make('no_hp_penghuni')
                                ->label('Nomor Telepon'),
                            ]),
                    
                    Tabs\Tab::make('Kontrak sewa')
                        ->schema([
                            TextInput::make('durasi_sewa')
                                ->label('Durasi Sewa'),
                            TextInput::make('total_tagihan')
                                ->label('Total Tagihan'),
                            TextInput::make('mulai_sewa')
                                ->label('Mulai Sewa')
                                ->helperText('Format tanggal: YYYY-MM-DD'),
                            TextInput::make('jatuh_tempo')
                                ->label('Jatuh Tempo')
                                ->helperText('Format tanggal: YYYY-MM-DD'),
                        ]),
                ]),
        ];
    }
}
