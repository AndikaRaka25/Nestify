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
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_kamar', ['Tipe A', 'Tipe B', 'Tipe C']);
            $table->string('nama_kamar');
            $table->foreignId('properti_id')->constrained('propertis')->onDelete('cascade');
            $table->enum('status_kamar', ['Aktif', 'Kosong'])->default('Kosong');
            $table->string('keterangan_kamar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
    
      
};
