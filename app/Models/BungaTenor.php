<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BungaTenor extends Model
{
    use HasFactory;

    protected $table = 'bunga_tenor';
    protected $fillable = ['tenor', 'bunga_percent'];

}

