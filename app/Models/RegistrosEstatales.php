<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrosEstatales extends Model
{
    use HasFactory;
    protected $table = 'registros_estatales';
    protected $fillable = [
        'lugar_atencion',
        'fecha_atencion',
        'folio_expediente',
        'factores_vulnerabilidades_id',
        'victimas_indirectas_id',
        'domicilio_notificaciones_id',
        'tipo_solicitantes_id',
        'datos_solicitantes_id',
        'autoridades_id',
        'generales_id'
    ];
}

