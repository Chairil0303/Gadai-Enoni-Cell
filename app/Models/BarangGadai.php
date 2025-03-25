<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Carbon\Carbon;


    class BarangGadai extends Model
    {
        use HasFactory;

        protected $table = 'barang_gadai'; // Sesuaikan dengan nama tabel di database
        protected $primaryKey = 'id_barang'; // Sesuaikan dengan primary key

        protected $fillable = [
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


        // Getter untuk menghitung sisa hari atau keterlambatan
        public function getTelatAttribute()
        {
            $tempo = Carbon::parse($this->tempo);
            $hariIni = Carbon::today();

            // Menghitung selisih hari (bisa positif atau negatif)
            return $hariIni->diffInDays($tempo, false);
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

    }
