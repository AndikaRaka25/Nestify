<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use App\Models\Tagihan; // ✅ 1. Import model Tagihan
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification; // ✅ 2. Import Notifikasi
use Illuminate\Database\Eloquent\Model; // ✅ 3. Import Model

class EditPenghuni extends EditRecord
{
    protected static string $resource = PenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * ✅ --- INI ADALAH LOGIKA BARU ANDA --- ✅
     * Metode ini akan berjalan secara otomatis SETELAH data penghuni berhasil disimpan.
     *
     * @param  array  $data Data yang baru saja disimpan.
     * @return void
     */
    protected function afterSave(): void
    {
        // Dapatkan data penghuni yang baru saja di-update
        $penghuni = $this->getRecord();

        // Cari tagihan terakhir yang belum lunas milik penghuni ini
        $lastUnpaidBill = Tagihan::where('penghuni_id', $penghuni->id)
                                 ->where('status', 'Belum Bayar')
                                 ->latest('created_at')
                                 ->first();

        // Jika ada tagihan yang belum lunas, kita update saja tagihan tersebut
        if ($lastUnpaidBill) {
            $lastUnpaidBill->update([
                'periode' => 'Pembaruan - ' . $penghuni->durasi_sewa,
                'total_tagihan' => $penghuni->total_tagihan,
                'jatuh_tempo' => $penghuni->jatuh_tempo,
            ]);

            Notification::make()
                ->title('Tagihan Berhasil Diperbarui!')
                ->body('Tagihan yang belum lunas untuk penghuni ini telah disesuaikan.')
                ->success()
                ->send();
        } else {
            // Jika semua tagihan sebelumnya sudah lunas, buat tagihan baru
            Tagihan::create([
                'penghuni_id' => $penghuni->id,
                'properti_id' => $penghuni->properti_id,
                'kamar_id' => $penghuni->kamar_id,
                'invoice_number' => 'INV/' . now()->year . '/' . uniqid(),
                'periode' => 'Tagihan Baru - ' . $penghuni->durasi_sewa,
                'total_tagihan' => $penghuni->total_tagihan,
                'jatuh_tempo' => $penghuni->jatuh_tempo,
                'status' => 'Belum Bayar',
            ]);

             Notification::make()
                ->title('Tagihan Baru Berhasil Dibuat!')
                ->body('Tagihan baru telah dibuat untuk penghuni ini sesuai durasi sewa yang baru.')
                ->success()
                ->send();
        }
    }
}
