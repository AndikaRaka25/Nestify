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
            // Mengubah kolom agar boleh bernilai NULL (nullable)
            $table->string('hubungan_kontak_darurat_penghuni')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            // Mengembalikan kolom menjadi tidak nullable jika migrasi di-rollback
            $table->string('hubungan_kontak_darurat_penghuni')->nullable(false)->change();
        });
    }
};
