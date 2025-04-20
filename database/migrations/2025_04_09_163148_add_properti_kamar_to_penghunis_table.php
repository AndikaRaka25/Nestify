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
            $table->foreignId('properti_id')->nullable()->after('id')->constrained('propertis')->onDelete('set null');
            $table->foreignId('kamar_id')->nullable()->unique()->after('properti_id')->constrained('kamars')->onDelete('set null'); // unique() memastikan 1 kamar hanya untuk 1 penghuni aktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            
            $table->dropForeign(['properti_id']);
            $table->dropForeign(['kamar_id']);
            $table->dropColumn(['properti_id', 'kamar_id']);
        });
    }
};