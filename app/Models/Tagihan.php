<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

   
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
        'applied_discount_code', 
        'applied_discount_details', 
    ];

    
    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'datetime',
        'applied_discount_code' => 'string', // Pastikan ini di-cast sebagai string
        'applied_discount_details' => 'array', // Pastikan ini di-cast sebagai array
    ];

     public function penghuni()  { 
        return $this->belongsTo(Penghuni::class, 'penghuni_id'); 
    }

    public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }
}
