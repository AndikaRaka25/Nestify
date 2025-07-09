<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'datetime',
    ];

    // Relasi ke Penghuni
    public function penghuni(): BelongsTo
    {
        return $this->belongsTo(Penghuni::class);
    }

    // Relasi ke Properti
    public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class);
    }

    // Relasi ke Kamar
    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }
}
