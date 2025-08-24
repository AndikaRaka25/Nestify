<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\KomplainSayaResource\Pages;
use App\Models\KelolaKomplain;
use App\Models\Penghuni; // Pastikan model ini di-import
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
use Filament\Notifications\Notification;

class KomplainSayaResource extends Resource
{
    protected static ?string $model = KelolaKomplain::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Komplain Saya';
    protected static ?string $modelLabel = 'Komplain';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\TextInput::make('nama_pelapor')
                    ->label('Nama Pelapor')
                    ->default(Auth::user()->name) // Tetap berfungsi saat membuat komplain baru
                    ->disabled(), 
                Forms\Components\TextInput::make('judul')->required()->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')->required()->columnSpanFull(),
                Forms\Components\FileUpload::make('lampiran')
                    ->multiple()->disk('public')->directory('komplain-attachments')->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'proses' => 'primary',
                        'selesai' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Diajukan')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\ViewAction::make()]);
    }

     protected static function mutateFormDataBeforeCreate(array $data): array
    {
        
        $userEmail = Auth::user()->email;
        $penghuniAktif = Penghuni::where('email_penghuni', $userEmail)
                                ->where('status_penghuni', 'Aktif')
                                ->first();

       
        if ($penghuniAktif) {
            $data['penghuni_id'] = $penghuniAktif->id;
            $data['properti_id'] = $penghuniAktif->properti_id;
            $data['kamar_id'] = $penghuniAktif->kamar_id; 
        }

        
        $data['status'] = 'pending';

        return $data;
    }

    public static function getEloquentQuery(): Builder
    {
        $userEmail = Auth::user()->email;
        $penghuniIds = Penghuni::where('email_penghuni', $userEmail)->pluck('id')->toArray();
        return parent::getEloquentQuery()->whereIn('penghuni_id', $penghuniIds);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKomplainSayas::route('/'),
            'create' => Pages\CreateKomplainSaya::route('/create'),
            'view' => Pages\ViewKomplainSaya::route('/{record}'),
        ];
    }
}
