<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiGadai extends Model
{
    use HasFactory;

    protected $table = 'transaksi_gadai'; // Nama tabel
    protected $primaryKey = 'id_transaksi'; // Primary key

    protected $fillable = [
        'id_nasabah',
        'id_user',
        'id_barang',
        'tanggal_gadai',
        'jumlah_pinjaman',
        'bunga',
        'jatuh_tempo',
        'no_bon',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    public function barang()
    {
        return $this->belongsTo(BarangGadai::class, 'id_barang', 'id_barang');
    }
}
// model transaksi gadai