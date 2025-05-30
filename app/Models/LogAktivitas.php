<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_users',
        'id_cabang',
        'kategori',
        'aksi',
        'deskripsi',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
        'waktu_aktivitas',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'waktu_aktivitas' => 'datetime',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Relasi ke cabang (opsional)
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }
}

