<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            // Kolom untuk menyimpan KODE promo, contoh: "HEMAT10"
            $table->string('applied_discount_code')->nullable()->after('bukti_pembayaran');

            // Kolom untuk menyimpan 'snapshot' detail diskon saat transaksi
            $table->json('applied_discount_details')->nullable()->after('applied_discount_code');
        });
    }

    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['applied_discount_code', 'applied_discount_details']);
        });
    }
};