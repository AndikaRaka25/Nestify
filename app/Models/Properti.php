<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Properti extends Model
{
    use HasFactory;
    protected $table = 'propertis';
    protected $fillable = [
        'user_id',
        'nama_properti',
        'alamat_properti',
        'jenis',
        'foto',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'harga_sewa', 
        'biaya_tambahan',
        'info_pembayaran',
        'discounts',

    ];
    protected $casts = [
        'foto' => 'array',
        'harga_sewa' => 'array', 
        'biaya_tambahan' => 'array', 
        'info_pembayaran' => 'array', 
        'discounts' => 'array', // Pastikan ini sesuai dengan tipe data yang Anda inginkan
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kamars()
    {
        return $this->hasMany(Kamar::class);
    }


    public function penghunis()
    {
        return $this->hasMany(Penghuni::class);
    }

     public function penghuni(): HasMany
    {
        return $this->hasMany(Penghuni::class);
    }

protected static function boot()
{
    parent::boot();

    static::deleted(function () {
        self::resetPropertiIds();
    });
}

public static function resetPropertiIds()
{
    DB::statement('SET @count = 0');
    DB::statement('UPDATE propertis SET id = @count := @count + 1');
    DB::statement('ALTER TABLE propertis AUTO_INCREMENT = 1');
}

}
