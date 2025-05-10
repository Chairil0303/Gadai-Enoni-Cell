<?php

// app/Models/Transaksi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'id_cabang',
        'jenis_transaksi',
        'arah',
        'nominal',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
