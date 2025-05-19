<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'no_bon',
        'id_nasabah',
        'id_cabang',
        'jenis_transaksi',
        'id_user',
        'arus_kas',
        'jumlah',
    ];

    // Relasi ke barang_gadai
    public function barangGadai()
    {
        return $this->belongsTo(BarangGadai::class, 'no_bon', 'no_bon');
    }

    // Relasi ke nasabah
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    // Relasi ke cabang
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_users');
    }
}
