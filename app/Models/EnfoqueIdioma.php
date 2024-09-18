<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueIdioma extends Model
{
    use HasFactory;
    protected $table = 'enfoques_idiomas';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
