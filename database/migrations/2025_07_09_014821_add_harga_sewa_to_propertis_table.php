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
            // Kolom untuk menyimpan harga sewa per tipe dalam format JSON
            $table->json('harga_sewa')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('propertis', function (Blueprint $table) {
            $table->dropColumn('harga_sewa');
        });
    }
};
