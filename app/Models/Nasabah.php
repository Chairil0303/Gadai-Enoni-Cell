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
        'id_users', // Relasi ke table users
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
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
    public function barangGadai()
{
    return $this->hasMany(BarangGadai::class, 'id_nasabah', 'id_nasabah');
}

}
