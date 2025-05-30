<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangLelang extends Model
{
    protected $table = 'barang_lelang';
    protected $guarded = [];

    public $timestamps = false;

    public function barangGadai()
    {
        return $this->belongsTo(BarangGadai::class, 'no_bon', 'no_bon');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }
}
