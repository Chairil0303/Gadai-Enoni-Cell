<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BungaTenor extends Model
{
    protected $table = 'bunga_tenor';
    protected $fillable = ['tenor', 'bunga_percent'];
}
