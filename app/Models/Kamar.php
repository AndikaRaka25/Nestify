<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\Penghuni;

class Kamar extends Model
{
    protected $table = 'kamars';
    protected $fillable = ['nama_kamar', 'tipe_kamar', 'status_kamar', 'keterangan_kamar', 'properti_id'];


    public function getStatusKamarAttribute($value)
    {
        return $this->attributes['keterangan_kamar'] === 'Terisi';
    }
   
    
    public function setStatusKamarAttribute($value)
    {
        $isAktif = (bool) $value;
        $this->attributes['status_kamar'] = $value ? 'Aktif' : 'Kosong';
        $this->attributes['keterangan_kamar'] = $value ? 'Terisi' : 'Kosong';
    }
public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class);
    }
    public function penghuni(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
         return $this->hasOne(Penghuni::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::updated(function (Kamar $kamar) {
            if ($kamar->wasChanged('keterangan_kamar') && $kamar->keterangan_kamar === 'Kosong') {
                $penghuni = $kamar->penghuni;  
                if ($penghuni) {
                    $penghuni->status_penghuni = 'Tidak Aktif';
                    $penghuni->save();
                }
            }

             elseif ($kamar->wasChanged('keterangan_kamar') && $kamar->keterangan_kamar === 'Terisi') {
               $penghuni = $kamar->penghuni;
               if ($penghuni && $penghuni->status_penghuni !== 'Aktif') {
                   
                    $penghuni->status_penghuni = 'Aktif';
                    $penghuni->save();
               }
             }
        });

        static::deleted(function (Kamar $kamar) { 
            $penghuni = $kamar->penghuni;
             if ($penghuni) {
                 $penghuni->status_penghuni = 'Tidak Aktif';
                 $penghuni->save();
             }
             self::resetKamarIds(); 
        });
    }

    public static function resetKamarIds()
    {
        
        DB::statement('SET @count = 0');
        DB::statement('UPDATE kamars SET id = @count := @count + 1');
        DB::statement('ALTER TABLE kamars AUTO_INCREMENT = 1');
    }
   
}
