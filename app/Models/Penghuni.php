<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penghuni extends Model
{
    protected $table = 'penghunis';

    // Mass assignment: daftar kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_penghuni',
        'alamat_penghuni',
        'no_hp_penghuni',
        'email_penghuni',
        'jenis_kelamin_penghuni',
        'pekerjaan_penghuni',
        'foto_ktp_penghuni',
        'nama_kontak_darurat_penghuni',
        'no_hp_kontak_darurat_penghuni',
        'hubungan_kontak_darurat_penghuni',
        'status_penghuni', 
        'durasi_sewa',
        'total_tagihan',
        'mulai_sewa',
        'jatuh_tempo',
        'kamar_id',    
        'properti_id',
    ];

    
    protected $casts = [
        'mulai_sewa' => 'date',
        'jatuh_tempo' => 'date',
    ];

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }
    public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class);
    }
    
  
    
}
