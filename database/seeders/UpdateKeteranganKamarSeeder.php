<?php

namespace Database\Seeders;

use App\Models\Kamar;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdateKeteranganKamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kamars = Kamar::all();
        
        foreach ($kamars as $kamar) {
            $kamar->keterangan_kamar = $kamar->status_kamar ? 'Terisi' : 'Kosong';
            $kamar->save();
        }
    }
}
