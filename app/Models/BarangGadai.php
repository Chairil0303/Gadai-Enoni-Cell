<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BarangGadai extends Model
{
    use HasFactory;

    protected $table = 'barang_gadai';
    protected $primaryKey = 'no_bon';
    public $incrementing = false;

    protected $fillable = [
        'no_bon',
        'id_nasabah',
        'nama_barang',
        'deskripsi',
        'tempo', 'tenor',
        'telat',
        'imei',
        'harga_gadai',
        'status',
        'id_kategori',
        'id_user',
    ];

    // Menambahkan pengecekan status dan mengupdate tempo dan telat jika sudah ditebus
    public function updateStatusDitebus()
    {
        if ($this->status === 'Ditebus') {
            // Jika status sudah Ditebus, atur tempo dan telat menjadi 0
            $this->tempo = Carbon::today();
            $this->telat = 0;
            $this->save();
        }
    }

    // Getter untuk menghitung sisa hari atau keterlambatan
    public function getTelatAttribute()
    {
        // Pastikan tidak menghitung telat jika sudah ditebus
        if ($this->status === 'Ditebus') {
            return 0;
        }

        $tempo = Carbon::parse($this->tempo);
        $hariIni = Carbon::today();

        return abs($hariIni->diffInDays($tempo, false));
    }

    // Method untuk menghitung bunga
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

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaksiGadai()
    {
        return $this->hasOne(TransaksiGadai::class, 'no_bon', 'no_bon');
    }
}
