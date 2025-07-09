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
            // Kolom untuk menyimpan biaya tambahan dalam format JSON
            $table->json('biaya_tambahan')->nullable()->after('harga_sewa');
        });
    }

    public function down(): void
    {
        Schema::table('propertis', function (Blueprint $table) {
            $table->dropColumn('biaya_tambahan');
        });
    }
};
