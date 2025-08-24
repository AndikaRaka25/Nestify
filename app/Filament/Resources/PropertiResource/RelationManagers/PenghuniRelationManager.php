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
    protected static string $relationship = 'penghuni'; 
    
    protected static ?string $recordTitleAttribute = 'nama_penghuni';
    
    protected static ?string $label = 'Penghuni Aktif';
    protected static ?string $pluralLabel = 'Penghuni Aktif';

    public function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status_penghuni', 'Aktif');
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_penghuni')
            ->columns([
                Tables\Columns\TextColumn::make('nama_penghuni')
                    ->label('Nama Penghuni')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar')
                    ->placeholder('Belum ditempatkan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo Berikutnya')
                    ->date('d F Y')
                    ->color('danger')
                    ->sortable(),
            ])
            ->filters([
                
            ])
            ->headerActions([
                
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Penghuni Baru')
                    ->url(fn (): string => PenghuniResource::getUrl('create', ['properti_id' => $this->getOwnerRecord()->id])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Penghuni $record): string => PenghuniResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make()
                    ->url(fn (Penghuni $record): string => PenghuniResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([]);
    }
}

