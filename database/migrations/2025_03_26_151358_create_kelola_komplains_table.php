<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelola_komplains', function (Blueprint $table) {
            $table->id();
            // Kunci asing untuk relasi
            $table->foreignId('penghuni_id')->constrained('penghunis')->onDelete('cascade');
            $table->foreignId('properti_id')->constrained('propertis')->onDelete('cascade');
            $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');

            // Detail Komplain
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lampiran')->nullable(); // Untuk menyimpan path gambar
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelola_komplains');
    }
};
