<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanPenghuniResource\Pages;
use App\Filament\Resources\PengajuanPenghuniResource\RelationManagers;
use App\Models\PengajuanPenghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanPenghuniResource extends Resource
{
    protected static ?string $model = PengajuanPenghuni::class;
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

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
            'index' => Pages\ListPengajuanPenghunis::route('/'),
            'create' => Pages\CreatePengajuanPenghuni::route('/create'),
            'edit' => Pages\EditPengajuanPenghuni::route('/{record}/edit'),
        ];
    }
}
