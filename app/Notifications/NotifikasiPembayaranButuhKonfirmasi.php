<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Filament\Resources\TagihanResource;
use App\Filament\Resources\TagihanResource\Pages\ListTagihans;


class NotifikasiPembayaranButuhKonfirmasi extends Notification
{
    use Queueable;

    public function __construct(
        public int $tagihanId,
        public string $invoiceNumber,
        public string $namaProperti,
        public string $namaPenghuni,
        public string $periode,
        public string $totalTagihan,
        public ?string $tanggalBayar = null,
        public ?string $metodeBayar = null,
        public ?string $urlDetail = null
    ) {}

    public function via($notifiable): array
    {
        return ['database','mail'];
    }

    private function resolveUrl(): string
    {
        return ListTagihans::getUrl(panel: 'admin'); 
    }

    public function toDatabase($notifiable): array
{
    return [
        'type'          => 'pembayaran_butuh_konfirmasi',
        'title'         => 'Pembayaran menunggu konfirmasi',
        'message'       => "INV {$this->invoiceNumber} ({$this->namaPenghuni} - {$this->namaProperti}) menunggu konfirmasi.",
        'tagihan_id'    => $this->tagihanId,
        'invoice'       => $this->invoiceNumber,
        'penghuni'      => $this->namaPenghuni,
        'properti'      => $this->namaProperti,
        'periode'       => $this->periode,
        'total'         => $this->totalTagihan,
        'tanggal_bayar' => $this->tanggalBayar,
        'metode_bayar'  => $this->metodeBayar,
        'url'           => $this->resolveUrl(),   // ← pakai helper di atas
    ];
}

public function toMail($notifiable): MailMessage
{
    return (new MailMessage)
        ->subject("Pembayaran Menunggu Konfirmasi - {$this->invoiceNumber}")
        ->greeting('Halo Pemilik,')
        ->line("Ada pembayaran dari {$this->namaPenghuni} untuk {$this->namaProperti}.")
        ->line("Invoice: {$this->invoiceNumber}")
        ->line("Periode: {$this->periode}")
        ->line("Total: Rp {$this->totalTagihan}")
        ->action('Tinjau Pembayaran', $this->resolveUrl()); // ← di sini juga
}
}
