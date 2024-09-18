<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVictima extends Model
{
    use HasFactory;
    protected $table = 'tipo_victimas';
    protected $fillable = [
        'clave',
        'nombre',
        'tipo_victimas_id'
    ];
}
