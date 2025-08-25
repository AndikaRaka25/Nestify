<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Filament\Resources\KelolaKomplainResource;
// ↓ sesuaikan namespace ini dengan proyekmu
use App\Filament\Resources\KelolaKomplainResource\Pages\ListKelolaKomplains;

class NotifikasiPemilik extends Notification
{
    use Queueable;

    public function __construct(
        public int $komplainId,
        public string $namaProperti,
        public string $namaPenghuni,
        public string $judulKomplain,
        public ?string $prioritas = null,
        public ?string $urlDetail = null
    ) {}

    public function via($notifiable): array
    {
        return ['database','mail'];
    }

    private function resolveUrl(): string
    {
        // Panel admin → halaman daftar komplain
     return $this->urlDetail ?? KelolaKomplainResource::getUrl(panel: 'admin');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'        => 'komplain_baru',
            'title'       => 'Komplain baru masuk',
            'message'     => "{$this->namaPenghuni} membuat komplain: {$this->judulKomplain} ({$this->namaProperti})",
            'komplain_id' => $this->komplainId,
            'properti'    => $this->namaProperti,
            'penghuni'    => $this->namaPenghuni,
            'prioritas'   => $this->prioritas,
            'url'         => $this->resolveUrl(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Komplain Baru Masuk')
            ->greeting('Halo Pemilik,')
            ->line("Ada komplain baru dari {$this->namaPenghuni} di {$this->namaProperti}.")
            ->line("Judul: {$this->judulKomplain}")
            ->when($this->prioritas, fn($m) => $m->line("Prioritas: {$this->prioritas}"))
            ->action('Tinjau Komplain', $this->resolveUrl());
    }
}
