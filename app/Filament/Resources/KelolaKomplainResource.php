<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelolaKomplainResource\Pages;
use App\Models\KelolaKomplain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB; 

class KelolaKomplainResource extends Resource
{
    protected static ?string $model = KelolaKomplain::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
         return $form
            ->schema([
                
                Forms\Components\Select::make('penghuni_id')
                    ->label('Pelapor')
                    ->relationship('penghuni', 'nama_penghuni') 
                    ->disabled(), // Dibuat read-only

                Forms\Components\Select::make('kamar_id')
                    ->label('Kamar')
                    ->relationship('kamar', 'nama_kamar') // Mengambil nama dari relasi 'kamar'
                    ->disabled(), // Dibuat read-only

                Forms\Components\TextInput::make('judul')->disabled(),
                Forms\Components\Textarea::make('deskripsi')->disabled()->columnSpanFull(),
                Forms\Components\FileUpload::make('lampiran')
                    ->label('Lampiran Foto')
                    ->multiple() 
                    ->image()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable(),
                Tables\Columns\TextColumn::make('penghuni.nama_penghuni')->label('Pelapor')->searchable(),
                Tables\Columns\TextColumn::make('kamar.nama_kamar')->label('Kamar'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'proses',
                        'success' => 'selesai',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Diajukan',
                        'proses' => 'Diproses',
                        'selesai' => 'Selesai',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Diajukan')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('proses')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (KelolaKomplain $record): bool => $record->status === 'pending')
                    ->action(function (KelolaKomplain $record) {
                        $record->status = 'proses';
                        $record->save();
                        Notification::make()->title('Komplain kini diproses!')->success()->send();
                    }),
                Action::make('selesaikan')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (KelolaKomplain $record): bool => $record->status === 'proses')
                    ->action(function (KelolaKomplain $record) {
                        $record->status = 'selesai';
                        $record->save();
                        Notification::make()->title('Komplain telah diselesaikan!')->success()->send();
                    }),
            ])
            ->bulkActions([]);
    }
    
   
   public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['pending', 'proses'])->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
         try {
            $count = DB::selectOne("SELECT COUNT(*) as count FROM kelola_komplains WHERE status IN ('pending', 'proses')")->count;
            return $count > 0 ? 'warning' : 'primary'; 
        } catch (\Exception $e) {
            return 'danger';
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaKomplains::route('/'),
        ];
    }
}
