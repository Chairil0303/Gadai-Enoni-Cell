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
            'id_cabang',
            'nama_barang',
            'deskripsi',
            'tempo', 'tenor',
            'telat',
            'imei',
            'harga_gadai',
            'status',
            'id_kategori',
            'id_user',
            'bunga'
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


        public function getTempoFormattedAttribute()
        {
            return Carbon::parse($this->tempo)->translatedFormat('l, d F Y');
        }

        // Getter untuk menghitung sisa hari atau keterlambatan
        public function getSisaHariAttribute()
        {
            if ($this->status === 'Ditebus') {
                return 0; // Jika sudah ditebus, sisa hari = 0
            }
            $tempo = Carbon::parse($this->tempo);
            $hariIni = Carbon::today();

            // Jika tempo masih di masa depan, hitung sisa hari. Jika tidak, sisa hari 0.
            return $hariIni->lessThan($tempo) ? $hariIni->diffInDays($tempo) : 0;
        }

        public function getTelatAttribute()
        {
            if ($this->status === 'Ditebus') {
                return 0;
            }

            $tempo = Carbon::parse($this->tempo);
            $hariIni = Carbon::today();

            // Jika tempo sudah lewat, hitung keterlambatan. Jika belum, telat = 0.
            return abs($hariIni->greaterThan($tempo) ? $hariIni->diffInDays($tempo) : 0);
        }

        public function getDendaAttribute()
    {
        return abs($this->harga_gadai * 0.01 * $this->telat);
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

        public function perpanjangan()
        {
            return $this->hasMany(PerpanjanganGadai::class, 'no_bon', 'no_bon');
        }
        public function cabang()
        {
            return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
        }
        public function bungaTenor()
        {
            return $this->belongsTo(BungaTenor::class, 'id_bunga_tenor', 'id');
        }
    }
