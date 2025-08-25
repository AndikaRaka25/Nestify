<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifikasiPengingatTagihan extends Notification
{
    use Queueable;

    public function __construct(
        public int $tagihanId,
        public string $invoiceNumber,
        public string $namaProperti,
        public string $namaPenghuni,
        public string $periode,
        public string $jatuhTempo,        // format 'Y-m-d'
        public string $totalTagihan,      // stringified untuk aman format
        public ?string $urlDetail = null  // link ke daftar/detail tagihan
    ) {}

    public function via($notifiable): array
    {
        return ['database','mail']; // simpan di DB + kirim email
    }

    private function resolveUrl(): string
    {
        if ($this->urlDetail) return $this->urlDetail;

        // Kalau ada Filament TagihanResource, pakai itu. Kalau tidak, fallback ke PengajuanPenghuni.
        if (class_exists(\App\Filament\Resources\TagihanResource::class)) {
            return \App\Filament\Resources\TagihanResource::getUrl(); // index
        }
        return \App\Filament\Resources\PengajuanPenghuniResource::getUrl();
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'          => 'pengingat_tagihan',
            'title'         => 'Pengingat tagihan belum dibayar',
            'message'       => "INV {$this->invoiceNumber} - {$this->namaPenghuni} ({$this->namaProperti}) masih belum dibayar. Jatuh tempo: {$this->jatuhTempo}.",
            'tagihan_id'    => $this->tagihanId,
            'invoice'       => $this->invoiceNumber,
            'properti'      => $this->namaProperti,
            'penghuni'      => $this->namaPenghuni,
            'periode'       => $this->periode,
            'total'         => $this->totalTagihan,
            'jatuh_tempo'   => $this->jatuhTempo,
            'url'           => $this->resolveUrl(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Pengingat Tagihan Belum Dibayar - {$this->invoiceNumber}")
            ->greeting('Halo Pemilik,')
            ->line("Tagihan {$this->invoiceNumber} untuk {$this->namaPenghuni} ({$this->namaProperti}) masih berstatus belum dibayar.")
            ->line("Periode: {$this->periode}")
            ->line("Jatuh tempo: {$this->jatuhTempo}")
            ->line("Total: Rp {$this->totalTagihan}")
            ->action('Tinjau Tagihan', $this->resolveUrl())
            ->line('Terima kasih.');
    }
}
