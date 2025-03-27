<?php 


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'kategori_barang'; // Harus sesuai dengan nama tabel
    protected $primaryKey = 'id_kategori'; // Pastikan sesuai dengan primary key tabel
    public $timestamps = false; // Matikan timestamps jika tidak ada di database

    protected $fillable = ['nama_kategori', 'deskripsi'];
}

