<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            // Mengubah kolom status_penghuni agar nilai default-nya menjadi 'Pengajuan'
            $table->enum('status_penghuni', ['Pengajuan', 'Aktif', 'Tidak Aktif'])
                  ->default('Pengajuan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            // Mengembalikan ke state semula jika migrasi di-rollback
            $table->enum('status_penghuni', ['Pengajuan', 'Aktif', 'Tidak Aktif'])
                  ->default('Aktif')->change();
        });
    }
};
