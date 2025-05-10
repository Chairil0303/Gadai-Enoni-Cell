<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nama',  // Ubah dari 'name' ke 'nama'
        'email',
        'username',
        'password',
        'role',
        'id_cabang',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }


    use Notifiable;

    // Cek jika user adalah superadmin
    public function isSuperadmin()
    {
        return $this->role === 'Superadmin';
    }

    // Cek jika user adalah admin
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }
    // Cek jika user adalah staff
    public function isStaf()
    {
        return $this->role === 'Staf';
    }

    // Cek jika user adalah nasabah
    public function isNasabah()
    {
        return $this->role === 'Nasabah';
    }


    public function nasabah()
    {
        return $this->hasOne(Nasabah::class, 'id_user'); // karena satu user hanya punya satu data nasabah
    }

}
