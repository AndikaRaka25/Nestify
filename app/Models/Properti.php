<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;



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

    ];
    protected $casts = [
        'foto' => 'array',
    ];
    public function kamars()
    {
        return $this->hasMany(Kamar::class);
    }

    public function penghunis()
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
