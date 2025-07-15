<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    /**
     * ✅ --- INI ADALAH PERBAIKAN UTAMA DAN SATU-SATUNYA --- ✅
     * Memastikan SEMUA kolom yang kita butuhkan saat create ada di sini.
     */
    protected $fillable = [
        'penghuni_id',
        'properti_id',
        'kamar_id',
        'invoice_number',
        'periode',
        'total_tagihan',
        'jatuh_tempo',
        'status',
        'bukti_pembayaran',
        'tanggal_bayar',
        'applied_discount_code', // KODE promo yang diterapkan
        'applied_discount_details', // Detail diskon yang diterapkan
    ];

    /**
     * The attributes that should be cast.
     * Ini akan memastikan kolom tanggal terbaca sebagai objek tanggal.
     */
    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'datetime',
        'applied_discount_code' => 'string', // Pastikan ini di-cast sebagai string
        'applied_discount_details' => 'array', // Pastikan ini di-cast sebagai array
    ];

    public function penghuni(): BelongsTo
    {
        return $this->belongsTo(Penghuni::class);
    }

    public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class);
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }
}
