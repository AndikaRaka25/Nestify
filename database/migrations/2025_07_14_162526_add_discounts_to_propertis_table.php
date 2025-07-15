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
            // Menambahkan kolom 'discounts' setelah 'info_pembayaran'
            // Tipe JSON bisa menyimpan banyak data diskon dalam satu baris
            $table->json('discounts')->nullable()->after('info_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propertis', function (Blueprint $table) {
            $table->dropColumn('discounts');
        });
    }
};
