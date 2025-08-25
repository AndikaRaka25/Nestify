<?php

namespace App\Observers;

use App\Models\Tagihan;
use App\Notifications\NotifikasiPembayaranButuhKonfirmasi;

class TagihanKonfirmasiObserver
{
    public function updated(Tagihan $tagihan): void
    {
        // Pastikan hanya saat transisi ke "Butuh Konfirmasi"
        $from = (string) $tagihan->getOriginal('status');
        $to   = (string) $tagihan->status;

        if ($from !== 'Butuh Konfirmasi' && $to === 'Butuh Konfirmasi') {
            $properti = $tagihan->properti;
            $pemilik  = $properti?->pemilik;
            $penghuni = $tagihan->penghuni;

            if (! $pemilik) return;

            $pemilik->notify(new NotifikasiPembayaranButuhKonfirmasi(
                tagihanId:     $tagihan->id,
                invoiceNumber: (string)$tagihan->invoice_number,
                namaProperti:  $properti->nama_properti ?? '-',
                namaPenghuni:  $penghuni->nama_penghuni ?? '-',
                periode:       (string)$tagihan->periode,
                totalTagihan:  (string)$tagihan->total_tagihan,
                tanggalBayar:  (string)($tagihan->tanggal_bayar ?? ''),   // kalau ada kolom
                metodeBayar:   (string)($tagihan->metode_bayar  ?? '')    // kalau ada kolom
            ));
        }
    }
}
