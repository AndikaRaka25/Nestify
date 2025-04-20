<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelolaKomplainResource\Pages;
use App\Filament\Resources\KelolaKomplainResource\RelationManagers;
use App\Models\KelolaKomplain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelolaKomplainResource extends Resource
{
    protected static ?string $model = KelolaKomplain::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
    public static function getNavigationBadgecolor(): ?string
{
    return static::getModel()::count() > 0 ? 'red' : null;
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaKomplains::route('/'),
            'create' => Pages\CreateKelolaKomplain::route('/create'),
            'edit' => Pages\EditKelolaKomplain::route('/{record}/edit'),
        ];
    }
}
