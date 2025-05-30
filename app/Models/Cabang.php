<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $primaryKey = 'id_cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'kontak',
        'google_maps_link'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_cabang','nama_cabang','alamat');
    }
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
    public function saldoCabang()
    {
        return $this->hasOne(SaldoCabang::class, 'id_cabang', 'id_cabang');
    }
}
