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
        Schema::table('propertis', function (Blueprint $table) {
            // Menambahkan kolom 'user_id' setelah kolom 'id'.
            // 'constrained' akan secara otomatis mengaitkannya dengan tabel 'users'.
            // 'cascadeOnDelete' berarti jika seorang user dihapus, semua propertinya juga akan ikut terhapus.
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained()
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propertis', function (Blueprint $table) {
            // Perintah untuk membatalkan migrasi jika diperlukan.
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
