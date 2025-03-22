<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangGadai extends Model
{
    use HasFactory;

    protected $table = 'barang_gadai'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'id_barang'; // Sesuaikan dengan primary key

    protected $fillable = [
        'id_nasabah',
        'nama_barang',
        'deskripsi',
        'status',
        'id_kategori',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }
}
