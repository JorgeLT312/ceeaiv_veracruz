<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HechosVictimizantes extends Model
{
    use HasFactory;
    protected $table = 'hechos_victimizantes';
    protected $fillable = [
    	'ambito_competencia',
        'fecha_hecho_ini',
        'fecha_hecho_fin',
        'recomendacion',
        'victimizante_hechos_id',
        'relato',
        'calle',
        'num_exterior',
        'num_interior',
        'colonia',
        'codigo_postal',
        'localidad',
    ];
}
