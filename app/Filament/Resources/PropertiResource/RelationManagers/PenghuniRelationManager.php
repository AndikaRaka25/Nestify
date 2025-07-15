<?php

namespace App\Filament\Resources\PropertiResource\RelationManagers;

use App\Filament\Resources\PenghuniResource;
use App\Models\Penghuni;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PenghuniRelationManager extends RelationManager
{
    // ✅ Memastikan relasi yang benar (biasanya jamak/plural)
    protected static string $relationship = 'penghuni'; 
    
    protected static ?string $recordTitleAttribute = 'nama_penghuni';
    
    // ✅ Mengubah label agar lebih deskriptif
    protected static ?string $label = 'Penghuni Aktif';
    protected static ?string $pluralLabel = 'Penghuni Aktif';

    /**
     * ✅ --- PEMBARUAN 1: Filter Data --- ✅
     * Method ini secara otomatis hanya akan mengambil penghuni
     * yang statusnya 'Aktif' dari properti ini.
     */
    public function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status_penghuni', 'Aktif');
    }

    // Form tidak lagi diperlukan karena semua aksi akan diarahkan
    // ke halaman resource utama. Cukup definisikan form kosong.
    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_penghuni')
            ->columns([
                // ✅ --- PEMBARUAN 2: Kolom Lebih Informatif --- ✅
                Tables\Columns\TextColumn::make('nama_penghuni')
                    ->label('Nama Penghuni')
                    ->searchable(),
                
                // Menampilkan kamar yang ditempati
                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar')
                    ->placeholder('Belum ditempatkan')
                    ->searchable(),

                // Menampilkan tanggal jatuh tempo
                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo Berikutnya')
                    ->date('d F Y')
                    ->color('danger')
                    ->sortable(),
            ])
            ->filters([
                // Filter tidak diperlukan karena kita sudah memfilter penghuni aktif
            ])
            ->headerActions([
                // ✅ --- PEMBARUAN 3: Aksi Lebih Cerdas --- ✅
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Penghuni Baru')
                    // Alihkan ke halaman "Create Penghuni" utama, dengan properti_id yang sudah terisi
                    ->url(fn (): string => PenghuniResource::getUrl('create', ['properti_id' => $this->getOwnerRecord()->id])),
            ])
            ->actions([
                // Arahkan ke halaman "View Penghuni" utama
                Tables\Actions\ViewAction::make()
                    ->url(fn (Penghuni $record): string => PenghuniResource::getUrl('view', ['record' => $record])),
                // Arahkan ke halaman "Edit Penghuni" utama
                Tables\Actions\EditAction::make()
                    ->url(fn (Penghuni $record): string => PenghuniResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([]);
    }
}
