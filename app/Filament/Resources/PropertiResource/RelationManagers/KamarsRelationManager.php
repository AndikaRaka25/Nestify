<?php

namespace App\Filament\Resources\PropertiResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
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
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\PropertiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\PropertiResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;


class KamarsRelationManager extends RelationManager
{
    protected static string $relationship = 'kamars';
    protected static ?string $recordTitleAttribute = 'nama_kamar';
    protected static ?string $label = 'Kamar';
    protected static ?string $pluralLabel = 'Kamar';
    protected static ?string $title = 'Daftar Kamar';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Silahkan isi data kamar')
                ->schema([
                    TextInput::make('nama_kamar')
                        ->label('Nama Kamar')
                        ->placeholder('Masukkan Nama Kamar')
                        ->required()
                        ->unique()
                        ->columnSpan(2),
                    Select::make('tipe_kamar')
                        ->label('Tipe Kamar')
                        ->placeholder('Pilih Tipe Kamar')
                        ->options([
                            'Tipe A' => 'Tipe A',
                            'Tipe B' => 'Tipe B',
                            'Tipe C' => 'Tipe C',
                        ])
                        ->required(),
                    
                    Select::make('status_kamar')
                        ->label('Status Kamar')
                        ->options([
                            'Aktif' => 'Aktif',
                            'Kosong' => 'Kosong',
                        ])
                        ->default('Kosong'),
                        
                ]) ->columns(2) ->columnspan(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_kamar')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Kamar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_kamar')
                    ->label('Tipe Kamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kamar')
                    ->label('Nama Kamar')
                    ->searchable()
                    ->sortable(),
                    ToggleColumn::make('status_kamar')
                    ->label('Status')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),
                    Tables\Columns\BadgeColumn::make('keterangan_kamar')
                    ->label('Keterangan Kamar')
                    ->color(fn (string $state): string => match ($state) {
                        'Terisi' => 'success',
                        'Kosong' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Tambah Kamar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
