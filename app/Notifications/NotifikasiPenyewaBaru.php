<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // optional, kalau mau dijalankan via queue
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Filament\Resources\PengajuanPenghuniResource;

class NotifikasiPenyewaBaru extends Notification // implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $penghuniId,
        public int $propertiId,
        public ?int $kamarId,
        public string $namaPenghuni,
        public string $namaProperti,
        public ?string $namaKamar = null,
        public ?string $urlDetail = null // link ke halaman detail
    ) {}

    private function resolveUrl(): string
{
    // Jika sebelumnya kamu kirim $this->urlDetail, biarkan jadi prioritas
    if ($this->urlDetail) {
        return $this->urlDetail;
    }
    // Arahkan ke halaman daftar PengajuanPenghuni (panel default 'admin')
    return PengajuanPenghuniResource::getUrl(); 
    // Jika kamu pakai multi-panel dan panelnya bukan default:
    // return PengajuanPenghuniResource::getUrl(panel: 'admin');
}
    public function via($notifiable): array
    {
        // Simpan di DB. Jika kamu ingin email juga, tambah 'mail'
        return ['database', 'mail']; // or: ['database','mail']
    }

   public function toDatabase($notifiable): array
{
    return [
        'type'        => 'penghuni_baru',
        'title'       => 'Penyewa baru terdaftar',
        'message'     => "{$this->namaPenghuni} mendaftar di {$this->namaProperti}" . ($this->namaKamar ? " ({$this->namaKamar})" : ''),
        'penghuni_id' => $this->penghuniId,
        'properti_id' => $this->propertiId,
        'kamar_id'    => $this->kamarId,
        'url'         => $this->resolveUrl(),
    ];
}

public function toMail($notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Penyewa baru terdaftar')
        ->greeting('Halo Pemilik,')
        ->line("{$this->namaPenghuni} mendaftar di {$this->namaProperti}" . ($this->namaKamar ? " ({$this->namaKamar})" : ''))
        ->action('Lihat detail', $this->resolveUrl());
}

}
