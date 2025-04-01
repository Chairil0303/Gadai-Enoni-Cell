<?php
// app/Models/TransaksiTebus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTebus extends Model
{
    use HasFactory;

    protected $table = 'transaksi_tebus';
    protected $primaryKey = 'id_transaksi_tebus';
    protected $fillable = [
        'no_bon', 'id_nasabah', 'tanggal_tebus', 'jumlah_pembayaran', 'status'
    ];
}
