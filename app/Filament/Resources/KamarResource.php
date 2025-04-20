<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kamar;
use App\Models\Properti;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Widgets\PropertiFilterWidget;
use App\Filament\Resources\KamarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KamarResource\RelationManagers;
use App\Filament\Resources\KamarResource\Widgets\PropertiFilter;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Get;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Model;


class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationBadgeTooltip = 'Jumlah Kamar';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('Silahkan isi data kamar')
                ->schema([
                    select::make('properti_id')
                    ->label('Pilih Properti anda')
                    ->relationship('properti', 'nama_properti')
                    ->placeholder('Pilih Properti Anda')
                    ->required()
                    ->reactive(),
                    TextInput::make('nama_kamar')
                        ->label('Nama Kamar')
                        ->placeholder('Masukkan Nama Kamar')
                        ->required()
                        ->unique(
                            modifyRuleUsing: function (Unique $rule, Get $get, ?Model $record) {
                                $propertiId = $get('properti_id');
                                if ($propertiId) {
                                    $rule = $rule->where('properti_id', $propertiId);
                                } else { 
                                }
                                if ($record) {
                                    $rule = $rule->ignore($record->getKey(), $record->getKeyName());
                                }
                                return $rule; 
                            },
                        ),
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
                        ->default('Kosong')
                        
                ]) ->columns(2) ->columnspan(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('properti.nama_properti')
                ->label('Properti')
                ->searchable()
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
                SelectFilter::make('properti_id')
                    ->relationship('properti', 'nama_properti')
                    ->placeholder('Pilih Properti')
                    ->multiple()
                    ->preload()
                    ->columnSpan(2)
                    ->label('Properti'),
            ])
            ->actions([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKamars::route('/'),
            'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }

}
