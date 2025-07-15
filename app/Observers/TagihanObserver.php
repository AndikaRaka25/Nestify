<?php

namespace App\Observers;

use App\Models\Tagihan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TagihanObserver
{
    public function updated(Tagihan $tagihan): void
    {
        if ($tagihan->isDirty('status') && $tagihan->status === 'Lunas') {
            
            $penghuni = $tagihan->penghuni;
            if (!$penghuni) return;

            $jumlahTagihanLunas = Tagihan::where('penghuni_id', $penghuni->id)
                                         ->where('status', 'Lunas')
                                         ->count();
            
            $parts = explode(' ', $penghuni->durasi_sewa);
            $durasiAngka = (int) ($parts[0] ?? 1);
            $durasiUnitText = $parts[1] ?? 'Bulan';
            $durasiUnit = strtolower(Str::of($durasiUnitText)->singular());

            if ($jumlahTagihanLunas < $durasiAngka) {
                
                // ✅ PERBAIKAN UTAMA: Hitung dari jatuh tempo tagihan yang BARU lunas
                $jatuhTempoSebelumnya = Carbon::parse($tagihan->jatuh_tempo);

                $jatuhTempoBerikutnya = $jatuhTempoSebelumnya->copy(); // Salin tanggal jatuh tempo sebelumnya
                match ($durasiUnit) {
                    'hari'   => $jatuhTempoBerikutnya->addDays(1),
                    'week', 'minggu'  => $jatuhTempoBerikutnya->addWeeks(1),
                    'month', 'bulan' => $jatuhTempoBerikutnya->addMonths(1),
                    'year', 'tahun'  => $jatuhTempoBerikutnya->addYears(1),
                };
                
                // Anda mungkin perlu logika ulang untuk harga sewa per periode di sini
                // Untuk sementara kita gunakan total_tagihan dari penghuni
                $hargaPeriodeBerikutnya = $penghuni->total_tagihan;

                Tagihan::create([
                    'penghuni_id' => $penghuni->id,
                    'properti_id' => $penghuni->properti_id,
                    'kamar_id' => $penghuni->kamar_id,
                    'invoice_number' => 'INV/' . now()->year . '/' . uniqid(),
                    'periode' => 'Tagihan ke-' . ($jumlahTagihanLunas + 1) . ' dari ' . $durasiAngka . ' ' . $durasiUnitText,
                    'total_tagihan' => $hargaPeriodeBerikutnya,
                    'jatuh_tempo' => $jatuhTempoBerikutnya,
                    'status' => 'Belum Bayar',
                ]);
            }
        }
    }
}
