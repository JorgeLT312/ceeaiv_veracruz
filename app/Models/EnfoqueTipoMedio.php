<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueTipoMedio extends Model
{
    use HasFactory;
    protected $table = 'enfoques_tipo_medios';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
