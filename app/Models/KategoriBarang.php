<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang'; // ini penting kalau nama tabel non-jamak

    protected $primaryKey = 'id_kategori'; // karena kamu pakai id_kategori, bukan id

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public $timestamps = true;
}
