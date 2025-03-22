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
        'nama',
        'nik',
        'alamat',
        'telepon',
        'status_blacklist',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status_blacklist' => 'boolean',
    ];
}
