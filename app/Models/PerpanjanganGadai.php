<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpanjanganGadai extends Model
{
    use HasFactory;

    protected $table = 'perpanjangan_gadai';

    protected $fillable = [
        'no_bon_lama',
        'no_bon_baru',
        'tenor_baru',
        'harga_gadai_baru',
        'bunga_baru',
        'tempo_baru',
        'id_cabang',
    ];

    public $timestamps = true;
}
