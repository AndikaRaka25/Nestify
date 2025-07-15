<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Mengubah tipe kolom 'lampiran' dari VARCHAR menjadi TEXT.
     */
    public function up(): void
    {
        Schema::table('kelola_komplains', function (Blueprint $table) {
            // Mengubah kolom 'lampiran' menjadi tipe TEXT yang bisa menampung data lebih panjang
            $table->text('lampiran')->nullable()->change();
        });
    }

    /**
     * Batalkan migrasi.
     * Mengembalikan tipe kolom ke VARCHAR jika diperlukan.
     */
    public function down(): void
    {
        Schema::table('kelola_komplains', function (Blueprint $table) {
            $table->string('lampiran', 255)->nullable()->change();
        });
    }
};
