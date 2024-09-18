<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VictimaDirecta extends Model
{
    use HasFactory;
    protected $table = 'victimas_directas';
    protected $fillable = [
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'delitos_id',
        'violaciones_id',
        'no_carpeta',
        'fiscalia',
        'victimas_directas_id',
        'observaciones_familiares',
        'registros_estatales_id',
        'ocupaciones_id',
        'sexos_id',
        'territorios_id',
        'paises_id',
        'entidades_federativas_id',
        'municipios_id',
        'curp',
        'poblacion',
    ];
}
