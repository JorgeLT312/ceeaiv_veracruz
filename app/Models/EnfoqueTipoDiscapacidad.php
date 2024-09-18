<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueTipoDiscapacidad extends Model
{
    use HasFactory;
    protected $table = 'enfoques_tipos_discapacidades';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
