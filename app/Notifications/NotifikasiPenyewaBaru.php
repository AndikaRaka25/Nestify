<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Contracts\Queue\ShouldQueue; // opsional

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
        public ?string $urlDetail = null
    ) {}

    public function via($notifiable): array
    {
        return ['database','mail'];  // ← pastikan 'database' ada
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
            'url'         => route('filament.admin.resources.pengajuan-penghunis.index'), 
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Penyewa baru terdaftar')
            ->greeting('Halo Pemilik,')
            ->line("{$this->namaPenghuni} mendaftar di {$this->namaProperti}" . ($this->namaKamar ? " ({$this->namaKamar})" : ''))
            ->action('Lihat detail', route('filament.admin.resources.pengajuan-penghunis.index'));
    }
}
