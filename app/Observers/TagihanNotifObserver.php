<?php

namespace App\Observers;

use App\Models\Tagihan;
use App\Notifications\NotifikasiPengingatTagihan;

class TagihanNotifObserver
{
    public function created(Tagihan $tagihan): void
    {
        // Hanya kirim jika status awal Belum Bayar
        if ($tagihan->status !== 'Belum Bayar') return;

        $properti = $tagihan->properti;
        $pemilik  = $properti?->pemilik;
        $penghuni = $tagihan->penghuni;

        if (! $pemilik) return;

        $pemilik->notify(new NotifikasiPengingatTagihan(
            tagihanId:     $tagihan->id,
            invoiceNumber: $tagihan->invoice_number,
            namaProperti:  $properti->nama_properti ?? '-',
            namaPenghuni:  $penghuni->nama_penghuni ?? '-',
            periode:       (string)$tagihan->periode,
            jatuhTempo:    (string)$tagihan->jatuh_tempo,
            totalTagihan:  (string)$tagihan->total_tagihan,
            urlDetail:     class_exists(\App\Filament\Resources\TagihanResource::class)
                ? \App\Filament\Resources\TagihanResource::getUrl()
                : \App\Filament\Resources\PengajuanPenghuniResource::getUrl()
        ));
    }
}
