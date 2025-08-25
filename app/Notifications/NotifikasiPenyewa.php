<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Filament\User\Resources\KomplainSayaResource;

class NotifikasiPenyewa extends Notification
{
    use Queueable;

    public function __construct(
        public int $komplainId,
        public string $namaProperti,
        public string $namaPenghuni,
        public string $judulKomplain,
        public ?string $catatanPenyelesaian = null,
        public ?string $urlDetail = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail']; // kirim email ke penyewa
    }
private function resolveUrl(): string
    {
        // Panel admin → halaman daftar komplain
     return $this->urlDetail ?? KomplainSayaResource::getUrl(panel: 'user');
    }
 
    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Komplain Anda Telah Diselesaikan')
            ->greeting('Halo, ' . $this->namaPenghuni)
            ->line("Komplain Anda di {$this->namaProperti} sudah diselesaikan.")
            ->line("Judul: {$this->judulKomplain}");

        if ($this->catatanPenyelesaian) {
            $mail->line("Catatan: {$this->catatanPenyelesaian}");
        }

        return $mail->action('Lihat Komplain', $this->resolveUrl());
    }
}
