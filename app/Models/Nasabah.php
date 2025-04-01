<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    protected $table = 'nasabah'; // Sesuaikan dengan nama tabel di database

    protected $primaryKey = 'id_nasabah'; // Sesuaikan dengan kolom ID utama

    protected $fillable = [
        'id_user', //ganti dari users ke user
        'nama',
        'nik',
        'alamat',
        'telepon',
        'status_blacklist',
    ];


    protected $casts = [
        'status_blacklist' => 'boolean',
    ];

    // Relasi ke table users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_users'); //colom di baranggadai nya id_user bukan id_users
    }

    public function userByUsername()
    {
        return $this->belongsTo(User::class, 'username', 'username'); // Relasi berdasarkan username
    }

    public function barangGadai()
    {
        return $this->hasMany(BarangGadai::class, 'id_nasabah', 'id_nasabah');
    }

    public function hitungBunga()
    {
        if ($this->tenor == 7) {
            $this->bunga = 0.05 * $this->harga_gadai;
        } elseif ($this->tenor == 14) {
            $this->bunga = 0.10 * $this->harga_gadai;
        } elseif ($this->tenor == 30) {
            $this->bunga = 0.15 * $this->harga_gadai;
        } else {
            $this->bunga = 0;
        }
        $this->save();
    }

}
