<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\KomplainSayaResource\Pages;
use App\Models\KelolaKomplain;
use App\Models\Penghuni; // Kita butuh ini untuk mencari data penghuni
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login

class KomplainSayaResource extends Resource
{
    protected static ?string $model = KelolaKomplain::class;

    // Mengatur tampilan di menu navigasi penyewa
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $label = 'Komplain Saya';
        protected static ?int $navigationSort = 3;
    protected static ?string $pluralLabel = 'Komplain Saya';

    public static function form(Form $form): Form
    {
        // Form ini hanya akan muncul saat penyewa menekan tombol "Buat Komplain Baru"
        return $form
            ->schema([
                // ✅ Menggunakan TextInput biasa, karena data akan disuntikkan dari halaman View
                Forms\Components\TextInput::make('nama_pelapor')
                    ->label('Nama Pelapor')
                    ->default(Auth::user()->name) // Tetap berfungsi saat membuat komplain baru
                    ->disabled(), // Selalu disabled
                
                Forms\Components\TextInput::make('judul')
                    ->label('Judul Komplain')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn (string $operation): bool => $operation !== 'create'),
                
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Lengkap Komplain')
                    ->required()
                    ->columnSpanFull()
                    ->disabled(fn (string $operation): bool => $operation !== 'create'),
                
                Forms\Components\FileUpload::make('lampiran')
                    ->label('Foto Lampiran')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->disk('public')
                    ->directory('komplain-attachments')
                    ->disabled(fn (string $operation): bool => $operation !== 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Tabel ini akan menampilkan daftar komplain yang pernah dibuat penyewa
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Diajukan')->dateTime()->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'proses',
                        'success' => 'selesai',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                // Filter tidak diperlukan di sini karena sudah ada Tabs
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Penyewa hanya bisa melihat detail
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListKomplainSayas::route('/'),
            'create' => Pages\CreateKomplainSaya::route('/create'),
            'view' => Pages\ViewKomplainSaya::route('/{record}'),
        ];
    }

    /**
     * !! BAGIAN PALING PENTING UNTUK KEAMANAN !!
     * Memastikan penyewa hanya bisa melihat komplain miliknya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        // 1. Cari data penghuni berdasarkan user yang sedang login
        $penghuni = Penghuni::where('email_penghuni', Auth::user()->email)->first();

        // 2. Jika user ini bukan penghuni, jangan tampilkan komplain apa pun
        if (!$penghuni) {
            return parent::getEloquentQuery()->whereNull('id');
        }

        // 3. Tampilkan komplain yang penghuni_id-nya cocok
        return parent::getEloquentQuery()->where('penghuni_id', $penghuni->id);
    }
}