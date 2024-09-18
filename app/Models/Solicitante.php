<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;
    protected $table = 'datos_solicitantes';
    protected $fillable = [
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'poblacion',
        'telefonos',
        'datos_solicitantes_id',
        'municipios_id',
        'territorios_id',
        'paises_id',
        'entidades_federativas_id',
        'sexos_id',
        'ambito_dependencias_id',
        'parentescos_id',
        'situaciones_migratorias_id',
        'identidad_probatorio_documentos_id',
        'documentos_probatorios_id',
    ];
}
