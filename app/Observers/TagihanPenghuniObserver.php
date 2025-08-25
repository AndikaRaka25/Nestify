<?php

namespace App\Observers;

use App\Models\Tagihan;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifikasiTagihanBaruPenghuni;

class TagihanPenghuniObserver
{
    public function created(Tagihan $tagihan): void
    {
        if ($tagihan->status !== 'Belum Bayar') return;

        $penghuni = $tagihan->penghuni;
        $properti = $tagihan->properti;

        if (empty($penghuni?->email_penghuni)) return;

        Notification::route('mail', $penghuni->email_penghuni)
            ->notify(new NotifikasiTagihanBaruPenghuni(
                invoiceNumber: (string)$tagihan->invoice_number,
                namaProperti:  $properti->nama_properti ?? '-',
                namaPenghuni:  $penghuni->nama_penghuni ?? '-',
                periode:       (string)$tagihan->periode,
                jatuhTempo:    (string)$tagihan->jatuh_tempo,
                totalTagihan:  (string)$tagihan->total_tagihan,
                urlDetail:     url('/') // ganti jika ada halaman penyewa
            ));
    }

    public function updated(Tagihan $tagihan): void
    {
        $from = (string) $tagihan->getOriginal('status');
        $to   = (string) $tagihan->status;

        // Transisi ke "Belum Bayar" → kirim email ke penyewa
        if ($from !== 'Belum Bayar' && $to === 'Belum Bayar') {
            $penghuni = $tagihan->penghuni;
            $properti = $tagihan->properti;

            if (empty($penghuni?->email_penghuni)) return;

            Notification::route('mail', $penghuni->email_penghuni)
                ->notify(new NotifikasiTagihanBaruPenghuni(
                    invoiceNumber: (string)$tagihan->invoice_number,
                    namaProperti:  $properti->nama_properti ?? '-',
                    namaPenghuni:  $penghuni->nama_penghuni ?? '-',
                    periode:       (string)$tagihan->periode,
                    jatuhTempo:    (string)$tagihan->jatuh_tempo,
                    totalTagihan:  (string)$tagihan->total_tagihan,
                    urlDetail:     url('/') // ganti jika ada halaman penyewa
                ));
        }
    }
}
