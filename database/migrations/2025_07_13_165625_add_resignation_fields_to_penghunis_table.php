<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            // Menambahkan kolom baru setelah 'status_penghuni'
            $table->text('alasan_berhenti')->nullable()->after('status_penghuni');
            $table->date('rencana_tanggal_keluar')->nullable()->after('alasan_berhenti');

            // Memperbarui pilihan status untuk mengakomodasi status baru
            $table->string('status_penghuni')->default('Pengajuan')->change();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            $table->dropColumn(['alasan_berhenti', 'rencana_tanggal_keluar']);
            $table->string('status_penghuni')->default('Aktif')->change();
        });
    }
};
