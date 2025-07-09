<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            // Kunci asing untuk menghubungkan ke tabel lain
            $table->foreignId('penghuni_id')->constrained('penghunis')->onDelete('cascade');
            $table->foreignId('properti_id')->constrained('propertis')->onDelete('cascade');
            $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');

            // Detail Tagihan
            $table->string('invoice_number')->unique();
            $table->string('periode'); // Contoh: "Juli 2025"
            $table->decimal('total_tagihan', 15, 2);
            $table->date('jatuh_tempo');
            
            // Status & Pembayaran
            $table->enum('status', ['Belum Bayar', 'Butuh Konfirmasi', 'Lunas'])->default('Belum Bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
