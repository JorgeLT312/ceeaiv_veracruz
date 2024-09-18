<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VictimaIndirecta extends Model
{
    use HasFactory;
    protected $table = 'victimas_indirectas';
    protected $fillable = [
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'presenta_identificacion',
        'victimas_indirectas_id',
        'documentos_probatorios_id',
        'parentescos_id',
        'identidad_probatorio_documentos_id',
    ];
}
