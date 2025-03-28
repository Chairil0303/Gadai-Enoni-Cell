<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori_barang'; // Pastikan sesuai dengan nama tabel di database
    protected $primaryKey = 'id_kategori';
    public $timestamps = false; // Jika tabel kategori tidak memiliki kolom created_at dan updated_at

    protected $fillable = ['nama_kategori'];
}
