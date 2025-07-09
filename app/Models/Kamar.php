<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Kamar extends Model
{
    use HasFactory;
    
    protected $table = 'kamars';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama_kamar', 
        'tipe_kamar', 
        'status_kamar', 
        'keterangan_kamar', 
        'properti_id'
    ];

    /**
     * Relasi ke Properti: Satu kamar dimiliki oleh satu properti.
     */
    public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class);
    }

    /**
     * Relasi ke Penghuni: Satu kamar dihuni oleh satu penghuni.
     */
    public function penghuni(): HasOne
    {
         return $this->hasOne(Penghuni::class);
    }
}
