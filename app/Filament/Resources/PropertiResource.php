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
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\PropertiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PropertiResource\RelationManagers;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

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
        Forms\Components\Grid::make()
            ->columns(3)
            ->schema([
                // Kolom kiri (2 kolom)
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\Section::make('Silahkan Isi Nama Properti Anda')
                            ->schema([     
                                TextInput::make('id')
                                    ->label('ID Properti') 
                                    ->default(fn () => (Properti::count() === 0 ? 1 : (Properti::max('id') ?? 0) + 1))
                                    ->readOnly()
                                    ->disabled(),
                                TextInput::make('nama_properti')
                                    ->label('Nama Properti')
                                    ->placeholder('Masukkan Nama Properti')
                                    ->required(),
                                Select::make('jenis')
                                    ->options([
                                        'putra' => 'Putra',
                                        'putri' => 'Putri',
                                        'campur' => 'Campur'
                                    ])
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Silahkan Isi Alamat Properti')
                            ->schema([
                                RichEditor::make('alamat_properti')  
                                    ->label('Alamat Properti')
                                    ->placeholder('Masukkan Alamat Properti')
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(2),

              
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\Section::make('Silahkan Upload Foto Properti Anda')
                            ->schema([
                                FileUpload::make('foto')
                                ->label('Foto Properti (Bisa Lebih dari Satu)') 
                                ->multiple() 
                                ->reorderable() 
                                ->disk('public')
                                ->directory('kos_foto') 
                                ->imageEditor()
                                ->maxFiles(5)
                            ]),
                        Forms\Components\Section::make('Silahkan Pilih pada Map')
                            ->schema([


                                TextInput::make('latitude')
                                    ->hidden()
                                    ->default(''),
                                TextInput::make('longitude')
                                    ->hidden()
                                    ->default(''),
                                TextInput::make('provinsi')
                                    ->required(),
                                TextInput::make('kabupaten')
                                    ->required(),
                                TextInput::make('kecamatan')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(1),
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