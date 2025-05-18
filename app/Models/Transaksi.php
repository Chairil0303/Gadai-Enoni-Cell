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
        'jumlah_transaksi',
        'keterangan',
        'tanggal_transaksi',
    ];

    public function barangGadai()
    {
        return $this->belongsTo(BarangGadai::class, 'no_bon', 'no_bon');
    }

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }
}