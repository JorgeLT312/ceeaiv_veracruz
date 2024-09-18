<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delitos extends Model
{
    use HasFactory;
    protected $table = 'delitos';
    protected $fillable = [
        'clave',
        'nombre',
        'delitos_id'
    ];
}
