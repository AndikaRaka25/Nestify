<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelolaKomplain extends Model
{
    use HasFactory;

    protected $table = 'kelola_komplains';

    protected $fillable = [
        'penghuni_id',
        'properti_id',
        'kamar_id',
        'judul',
        'deskripsi',
        'lampiran',
        'status',
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
