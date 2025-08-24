<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifikasiAjukanBerhenti extends Notification
{
    use Queueable;

    public function __construct(
        public int $penghuniId,
        public int $propertiId,
        public ?int $kamarId,
        public string $namaPenghuni,
        public string $namaProperti,
        public ?string $alasanBerhenti = null,
        public ?string $rencanaTanggalKeluar = null,
        public ?string $urlDetail = null,
    ) {}

    public function via($notifiable): array
    {
        // Simpan ke database dan kirim email (email opsional, bisa dihapus)
        return ['database','mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'        => 'ajukan_berhenti',
            'title'       => 'Permintaan berhenti dari penghuni',
            'message'     => "{$this->namaPenghuni} mengajukan berhenti di {$this->namaProperti}" .
                             ($this->rencanaTanggalKeluar ? " (rencana keluar: {$this->rencanaTanggalKeluar})" : ''),
            'penghuni_id' => $this->penghuniId,
            'properti_id' => $this->propertiId,
            'kamar_id'    => $this->kamarId,
            'alasan'      => $this->alasanBerhenti,
            'rencana_keluar' => $this->rencanaTanggalKeluar,
            'url'         => route('filament.admin.resources.pengajuan-penghunis.index'),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Permintaan Berhenti dari Penghuni')
            ->greeting('Halo Pemilik,')
            ->line("{$this->namaPenghuni} mengajukan berhenti di {$this->namaProperti}.")
            ->line($this->rencanaTanggalKeluar ? "Rencana tanggal keluar: {$this->rencanaTanggalKeluar}" : '')
            ->line($this->alasanBerhenti ? "Alasan: {$this->alasanBerhenti}" : '');

        return $mail->action('Tinjau Permintaan', $this->urlDetail ?? url('filament.admin.resources.pengajuan-penghunis.index'));
    }
}
