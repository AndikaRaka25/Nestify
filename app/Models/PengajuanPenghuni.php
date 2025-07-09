<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPenghuni extends Penghuni // Meng-extend model Penghuni
{
    use HasFactory;

    // Menentukan tabel yang digunakan tetap 'penghunis'
    protected $table = 'penghunis';

    // Secara otomatis menambahkan filter global untuk hanya menampilkan
    // penghuni dengan status 'Pengajuan'.
    protected static function booted(): void
    {
        static::addGlobalScope('hanyaPengajuan', function ($builder) {
            $builder->where('status_penghuni', 'Pengajuan');
        });
    }
}
