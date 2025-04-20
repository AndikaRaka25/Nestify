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
        Schema::create('penghunis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penghuni');
            $table->string('alamat_penghuni');
            $table->string('no_hp_penghuni');
            $table->string('email_penghuni');
            $table->enum('jenis_kelamin_penghuni', ['Laki-Laki', 'Perempuan', 'Tidak Diketahui']);
            $table->string('pekerjaan_penghuni');
            $table->string('foto_ktp_penghuni')->nullable();
            $table->string('nama_kontak_darurat_penghuni');
            $table->string('no_hp_kontak_darurat_penghuni');
            $table->string('hubungan_kontak_darurat_penghuni');
            $table->enum('status_penghuni', ['Pengajuan', 'Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('durasi_sewa')->nullable();
            $table->decimal('total_tagihan', 15, 2)->nullable();
            $table->date('mulai_sewa')->nullable();
            $table->date('jatuh_tempo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghunis');
      
    }
};
