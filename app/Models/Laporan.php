<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_transaksi',
        'id_lelang',
        'tipe_laporan',
        'keterangan',
        'jumlah',
        'tanggal_laporan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiGadai::class, 'id_transaksi', 'id_transaksi');
    }

    public function lelang()
    {
        return $this->belongsTo(LelangBarang::class, 'id_lelang', 'id_lelang');
    }
}

