<?php

namespace App\Filament\Resources\KamarResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenghuniRelationManager extends RelationManager
{
    protected static string $relationship = 'penghuni';

    // Judul yang akan ditampilkan di atas tabel
    protected static ?string $title = 'Data Penghuni Kamar Ini';

    public function form(Form $form): Form
    {
        // Kita biarkan kosong karena kita tidak akan membuat/mengedit penghuni dari sini
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_penghuni')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_penghuni')
            ->columns([
                Tables\Columns\TextColumn::make('nama_penghuni')
                    ->label('Nama Penghuni'),
                Tables\Columns\TextColumn::make('no_hp_penghuni')
                    ->label('Nomor HP'),
                Tables\Columns\TextColumn::make('mulai_sewa')
                    ->label('Mulai Sewa')
                    ->date(),
                Tables\Columns\BadgeColumn::make('status_penghuni')
                    ->label('Status')
                     ->color(fn (string $state): string => match ($state) {
                        'Pengajuan' => 'warning',
                        'Aktif' => 'success',
                        'Tidak Aktif' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Kita nonaktifkan tombol 'Create' karena penghuni dibuat dari menu utama
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Hanya izinkan untuk melihat detail, bukan mengedit dari sini
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
