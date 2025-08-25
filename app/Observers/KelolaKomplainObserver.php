<?php

namespace App\Observers;

use App\Models\KelolaKomplain;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifikasiPenyewa;
use App\Notifications\NotifikasiPemilik;

class KelolaKomplainObserver
{
    // Komplain baru dibuat → notifikasi ke PEMILIK
    public function created(KelolaKomplain $komplain): void
    {
        $properti = $komplain->properti;
        $pemilik  = $properti?->pemilik;
        $penghuni = $komplain->penghuni;

        if (! $pemilik) return;

        $pemilik->notify(new NotifikasiPemilik(
            komplainId:    $komplain->id,
            namaProperti:  $properti->nama_properti ?? '-',
            namaPenghuni:  $penghuni->nama_penghuni ?? '-',
            judulKomplain: (string)$komplain->judul ?? 'Komplain Baru',
            prioritas:     (string)($komplain->prioritas ?? ''), // jika ada
            urlDetail:    null, // biarkan default
        ));
    }

    // Komplain berubah -> jika status menjadi "Selesai" → email ke PENYEWA
    public function updated(KelolaKomplain $komplain): void
    {
        $from = (string) $komplain->getOriginal('status');
        $to   = (string) $komplain->status;

        if ($from !== 'pending' && $to === 'selesai') {
            $penghuni = $komplain->penghuni;
            $properti = $komplain->properti;

            if (empty($penghuni?->email_penghuni)) return;

            Notification::route('mail', $penghuni->email_penghuni)
                ->notify(new NotifikasiPenyewa(
                    namaPenghuni:       $penghuni->nama_penghuni ?? '-',
                    namaProperti:       $properti->nama_properti ?? '-',
                    judulKomplain:      (string)$komplain->judul ?? 'Komplain',
                    catatanPenyelesaian:(string)($komplain->catatan_penyelesaian ?? ''),
                    urlDetail:          null, // biarkan default
                    komplainId:         $komplain->id
                ));
        }
    }
}
