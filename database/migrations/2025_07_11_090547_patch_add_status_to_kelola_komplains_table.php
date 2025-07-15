<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama kelas bisa Anda sesuaikan, tetapi isinya harus seperti ini.
return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Perintah ini akan menambahkan kolom 'status' ke tabel 'kelola_komplains'
     * HANYA JIKA kolom tersebut belum ada.
     */
    public function up(): void
    {
        Schema::table('kelola_komplains', function (Blueprint $table) {
            // Cek terlebih dahulu apakah kolom 'status' sudah ada atau belum
            if (!Schema::hasColumn('kelola_komplains', 'status')) {
                // Jika tidak ada, maka tambahkan kolomnya.
                $table->enum('status', ['pending', 'proses', 'selesai'])
                      ->default('pending')
                      ->after('lampiran'); // Menempatkan kolom setelah kolom 'lampiran'
            }
        });
    }

    /**
     * Batalkan migrasi.
     * Perintah ini akan menghapus kolom 'status' JIKA kolom tersebut ada.
     */
    public function down(): void
    {
        Schema::table('kelola_komplains', function (Blueprint $table) {
            // Cek terlebih dahulu untuk keamanan
            if (Schema::hasColumn('kelola_komplains', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
