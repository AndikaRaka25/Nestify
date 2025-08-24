<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; 

class Penghuni extends Model
{
    protected $table = 'penghunis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'status_penghuni',
        'alasan_berhenti', 
        'rencana_tanggal_keluar',
        'durasi_sewa',
        'total_tagihan',
        'mulai_sewa',
        'jatuh_tempo',
        'kamar_id',
        'properti_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mulai_sewa' => 'date',
        'jatuh_tempo' => 'date',
        'rencana_tanggal_keluar' => 'date',
    ];

    /**
     * Mendefinisikan relasi: Satu Penghuni menempati satu Kamar.
     */
    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }

    /**
     * Mendefinisikan relasi: Satu Penghuni berada di satu Properti.
     */


    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class);
    }

// Penghuni.php
public function properti() { return $this->belongsTo(Properti::class,'properti_id'); }

   
    protected static function booted(): void
    {
        
        static::created(function (Penghuni $penghuni) {
            // 1. Update status kamar menjadi 'Aktif' (Terisi)
            if ($penghuni->kamar_id) {
                $kamar = Kamar::find($penghuni->kamar_id);
                if ($kamar) {
                    $kamar->status_kamar = 'Aktif';
                    $kamar->keterangan_kamar = 'Terisi'; // Untuk konsistensi tampilan
                    $kamar->save();
                }
            }

            // 2. Buat tagihan pertama secara otomatis jika statusnya 'Aktif'
            if ($penghuni->status_penghuni === 'Aktif') {
                Tagihan::create([
                    // Mengambil relasi dari data penghuni yang baru dibuat
                    'penghuni_id' => $penghuni->id,
                    'properti_id' => $penghuni->properti_id,
                    'kamar_id' => $penghuni->kamar_id,

                    // Mengambil data tagihan dari data penghuni
                    'total_tagihan' => $penghuni->total_tagihan,
                    'jatuh_tempo' => $penghuni->jatuh_tempo,
                    
                    // Membuat info unik untuk tagihan ini
                    'invoice_number' => 'INV-' . now()->year . now()->month . '-' . Str::upper(Str::random(6)),
                    'periode' => now()->format('F Y'), // Contoh: "July 2025"
                    'status' => 'Belum Bayar', // Status awal tagihan
                ]);
            }
        });

        
        static::deleted(function (Penghuni $penghuni) {
            // Update status kamar kembali menjadi 'Kosong'
            if ($penghuni->kamar_id) {
                $kamar = Kamar::find($penghuni->kamar_id);
                if ($kamar) {
                    $kamar->status_kamar = 'Kosong';
                    $kamar->keterangan_kamar = 'Kosong';
                    $kamar->save();
                }
            }
        });
    }
}
