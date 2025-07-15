<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use App\Models\Tagihan;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

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

    
    protected function afterSave(): void
    {
        $penghuni = $this->getRecord();

       
        $totalTagihanAwal = (float) $penghuni->total_tagihan;

        $penghuni->load('properti');
        $biayaTambahan = $penghuni->properti->biaya_tambahan ?? [];

        
        $totalBiayaTambahan = 0;
        if (is_array($biayaTambahan)) {
            foreach ($biayaTambahan as $biaya) {
                $totalBiayaTambahan += (float) ($biaya['total_biaya'] ?? 0);
            }
        }

      
        $totalTagihanAkhir = $totalTagihanAwal + $totalBiayaTambahan;

        
        $lastUnpaidBill = Tagihan::where('penghuni_id', $penghuni->id)
                                 ->where('status', 'Belum Bayar')
                                 ->latest('created_at')
                                 ->first();

        
        if ($lastUnpaidBill) {
            
            $lastUnpaidBill->update([
                'periode' => 'Pembaruan - ' . $penghuni->durasi_sewa,
                'total_tagihan' => $totalTagihanAkhir, 
                'jatuh_tempo' => $penghuni->jatuh_tempo,
            ]);

            Notification::make()
                ->title('Tagihan Berhasil Diperbarui!')
                ->body('Tagihan yang belum lunas untuk penghuni ini telah disesuaikan.')
                ->success()
                ->send();
        } else {
            
            Tagihan::create([
                'penghuni_id' => $penghuni->id,
                'properti_id' => $penghuni->properti_id,
                'kamar_id' => $penghuni->kamar_id,
                'invoice_number' => 'INV/' . now()->year . '/' . uniqid(),
                'periode' => 'Tagihan Baru - ' . $penghuni->durasi_sewa,
                'total_tagihan' => $totalTagihanAkhir, 
                'jatuh_tempo' => $penghuni->jatuh_tempo,
                'status' => 'Belum Bayar',
            ]);

             Notification::make()
                ->title('Tagihan Baru Berhasil Dibuat!')
                ->body('Tagihan baru telah dibuat sesuai durasi sewa yang baru.')
                ->success()
                ->send();
        }
    }
}
