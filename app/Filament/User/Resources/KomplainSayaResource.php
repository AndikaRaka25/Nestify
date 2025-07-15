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

    // ==============================================================
    // ✨ INILAH PERBAIKAN UTAMA DAN PALING PENTING ✨
    // ==============================================================
     protected static function mutateFormDataBeforeCreate(array $data): array
    {
        // Langkah 1: Dapatkan email dari pengguna yang sedang login.
        // Ini adalah satu-satunya penghubung yang valid.
        $userEmail = Auth::user()->email;

        // Langkah 2: Cari data sewa (penghuni) yang statusnya "Aktif"
        // menggunakan email tersebut.
        $penghuniAktif = Penghuni::where('email_penghuni', $userEmail)
                                ->where('status_penghuni', 'Aktif')
                                ->first();

        // Langkah 3: Pastikan data penghuni aktif ditemukan. Jika ya,
        // ambil semua ID yang diperlukan dari sana.
        if ($penghuniAktif) {
            $data['penghuni_id'] = $penghuniAktif->id;
            $data['properti_id'] = $penghuniAktif->properti_id;
            $data['kamar_id'] = $penghuniAktif->kamar_id; // kamar_id PASTI terisi
        }

        // Langkah 4: Atur status awal komplain menjadi 'pending'.
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
