<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifikasiTagihanBaruPenghuni extends Notification
{
    use Queueable;

    public function __construct(
        public string $invoiceNumber,
        public string $namaProperti,
        public string $namaPenghuni,   // tambahkan properti namaPenghuni
        public string $periode,
        public string $jatuhTempo,     // format "Y-m-d" (string)
        public string $totalTagihan,   // string untuk aman format
        public ?string $urlDetail = null // halaman informasi tagihan utk penyewa (ganti jika ada)
    ) {}

    public function via($notifiable): array
    {
        // Penyewa bukan model User → kirim email saja
        return ['mail'];
    }

    private function resolveUrl(): string
    {
        // Ganti ke halaman/route yang dipakai penyewa jika ada
        return $this->urlDetail ?? url('/');
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Tagihan Baru - {$this->invoiceNumber}")
            ->greeting("Halo, {$this->namaPenghuni}")
            ->line("Ada tagihan baru untuk {$this->namaProperti}.")
            ->line("Invoice: {$this->invoiceNumber}")
            ->line("Periode: {$this->periode}")
            ->line("Jatuh Tempo: {$this->jatuhTempo}")
            ->line("Total: Rp {$this->totalTagihan}")
            ->action('Lihat Tagihan', $this->resolveUrl())
            ->line('Terima kasih.');
    }
}
