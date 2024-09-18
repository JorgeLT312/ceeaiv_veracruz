<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autoridades extends Model
{
    use HasFactory;
    protected $table = 'autoridades';
    protected $fillable = [
    	'autoridades_id',
        'observaciones',
        'generales_id',
        'tipo_danio_id',
        'nombre_autoridad',
        'denuncio_ante_ministerio',
        'fecha',
        'competencia',
        'entidades_federativas_id',
        'delitos_id',
        'violaciones_id',
        'agencia_mp',
        'ap_ci_ac',
        'estado_investigacion',
        'num_juzgado',
        'num_proceso',
        'estado_proceso_judicial',
        'organismo',
        'autoridad_responsable',
        'tipo_resolucion',
        'folio',
        'estado_actual',
        'presento_queja_ddhh',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'institucion',
        'nombre_otra_autoridad'
    ];
}