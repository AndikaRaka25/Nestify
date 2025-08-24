<?php

namespace App\Observers;

use App\Models\Penghuni;
use App\Notifications\NotifikasiPenyewaBaru;
use App\Notifications\NotifikasiAjukanBerhenti;
use App\Filament\Resources\PengajuanPenghuniResource;

class PenghuniObserver
{
    public function created(Penghuni $penghuni): void
    {
        $properti = $penghuni->properti;
        $pemilik  = $properti?->pemilik;
$url = PengajuanPenghuniResource::getUrl();
        if (! $pemilik) return;

        $namaKamar = $penghuni->kamar->nama_kamar ?? null;

        $pemilik->notify(new NotifikasiPenyewaBaru(
            penghuniId:  $penghuni->id,
            propertiId:  $properti->id,
            kamarId:     $penghuni->kamar_id,
            namaPenghuni:$penghuni->nama_penghuni,
            namaProperti:$properti->nama_properti,
            namaKamar:   $namaKamar,
            urlDetail:   route('filament.admin.resources.pengajuan-penghunis.index') // ganti sesuai route/page kamu
        ));
    }

    public function updated(Penghuni $penghuni): void
    {
        $url = PengajuanPenghuniResource::getUrl();
         // ======== Trigger: Ajukan Berhenti ========
        $statusChanged = $penghuni->wasChanged('status_penghuni');
        $hasBerhentiInStatus = $statusChanged && stripos($penghuni->status_penghuni, 'berhenti') !== false;

        $alasanChanged  = $penghuni->wasChanged('alasan_berhenti') && !empty($penghuni->alasan_berhenti);
        $rencanaChanged = $penghuni->wasChanged('rencana_tanggal_keluar') && !empty($penghuni->rencana_tanggal_keluar);

        if ($hasBerhentiInStatus || $alasanChanged || $rencanaChanged) {
            $properti = $penghuni->properti;
            $pemilik  = $properti?->pemilik;
            if (! $pemilik) {
                return;
            }

            $url = null;
            // Arahkan ke halaman pemilik untuk meninjau/menindaklanjuti:
            // Opsi A (Filament Resource Penghuni):
            // $url = \App\Filament\Resources\PenghuniResource::getUrl('view', ['record' => $penghuni->id]);
            // Opsi B (Route kustom ke detail Penghuni):
            $url =  route('filament.admin.resources.pengajuan-berhenti.index', $penghuni->id); // ganti sesuai rute yang ada di aplikasi Anda

            $pemilik->notify(new NotifikasiAjukanBerhenti(
                penghuniId: $penghuni->id,
                propertiId: $properti->id,
                kamarId:    $penghuni->kamar_id,
                namaPenghuni: $penghuni->nama_penghuni,
                namaProperti: $properti->nama_properti,
                alasanBerhenti: $penghuni->alasan_berhenti,
                rencanaTanggalKeluar: optional($penghuni->rencana_tanggal_keluar)->format('Y-m-d') ?? (string)$penghuni->rencana_tanggal_keluar,
                urlDetail: $url
            ));
        }
        if ($penghuni->wasChanged('status_penghuni') && $penghuni->status_penghuni === 'Aktif') {
            $properti = $penghuni->properti;
            $pemilik  = $properti?->pemilik;
            if (! $pemilik) return;

            $pemilik->notify(new NotifikasiPenyewaBaru(
                penghuniId:  $penghuni->id,
                propertiId:  $properti->id,
                kamarId:     $penghuni->kamar_id,
                namaPenghuni:$penghuni->nama_penghuni,
                namaProperti:$properti->nama_properti,
                namaKamar:   null,
                urlDetail:   route('filament.admin.resources.pengajuan-penghunis.index')
            ));
        }
    }
}
