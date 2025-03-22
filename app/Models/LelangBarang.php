<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LelangBarang extends Model
{
    use HasFactory;

    protected $table = 'lelang_barang'; // Nama tabel
    protected $primaryKey = 'id_lelang'; // Primary key

    protected $fillable = [
        'id_barang',
        'tanggal_lelang',
        'harga_awal',
        'status_penjualan',
    ];

    public function barang()
    {
        return $this->belongsTo(BarangGadai::class, 'id_barang', 'id_barang');
    }
}
