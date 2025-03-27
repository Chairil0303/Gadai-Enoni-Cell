<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';

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

    public function isNasabah()
    {
        return $this->role === 'Nasabah';
    }


}
