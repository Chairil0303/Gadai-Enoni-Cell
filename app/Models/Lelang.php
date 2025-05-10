<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lelang extends Model
{
    use HasFactory;

    protected $table = 'lelang'; // Nama tabel
    protected $primaryKey = 'id_lelang'; // Primary key

    public $timestamps = true; // Menggunakan timestamp

    // Jika Anda ingin menggunakan nama kolom lain untuk created_at dan updated_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'barang_gadai_no_bon',
        'kondisi_barang',
        'keterangan',
        'foto_barang',
        'harga_lelang',
    ];

    public function barangGadai()
    {
        return $this->belongsTo(BarangGadai::class, 'barang_gadai_no_bon', 'no_bon');
    }

}
