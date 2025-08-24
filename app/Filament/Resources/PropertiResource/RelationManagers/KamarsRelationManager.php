<?php

namespace App\Filament\Resources\PropertiResource\RelationManagers;

use App\Models\Kamar; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use App\Filament\Resources\KamarResource; 

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
                     Forms\Components\Select::make('tipe_kamar')
                        ->label('Tipe Kamar')
                        ->placeholder('Pilih Tipe Kamar yang Tersedia')
                        ->options(function (RelationManager $livewire): array {
                            $properti = $livewire->getOwnerRecord();
                            if (!$properti || empty($properti->harga_sewa)) {
                                return [];
                            }
                            return collect($properti->harga_sewa)
                                ->pluck('tipe', 'tipe')
                                ->all();
                        })
                        ->required(),

                    Forms\Components\TextInput::make('nama_kamar')
                        ->label('Nama atau Nomor Kamar')
                        ->placeholder('Contoh: Kamar 01, Kamar A-3')
                        ->required()
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: function (Unique $rule, RelationManager $livewire) {
                                return $rule->where('properti_id', $livewire->getOwnerRecord()->id);
                            }
                        ),
                    
                   
                        
                ]) ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_kamar')
            ->columns([
                Tables\Columns\TextColumn::make('tipe_kamar')
                    ->label('Tipe Kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kamar')
                    ->label('Nama Kamar')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('penghuni_aktif')
                    ->label('Dihuni Oleh')
                    ->getStateUsing(function (Kamar $record): string {
                        $penghuni = $record->penghuni()
                                          ->where('status_penghuni', 'Aktif')
                                          ->first();
                        return $penghuni?->nama_penghuni ?? 'Kosong';
                    }),
                
                Tables\Columns\BadgeColumn::make('status_kamar')
                    ->label('Status Kamar')
                    ->colors([
                        'danger' => 'Kosong',
                        'success' => 'Aktif',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Kamar')
                    ->url(fn (): string => KamarResource::getUrl('create', ['properti_id' => $this->getOwnerRecord()->id])),
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

