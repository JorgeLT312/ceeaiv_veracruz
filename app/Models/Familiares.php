<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familiares extends Model
{
    use HasFactory;
    protected $table = 'familiares';
    protected $fillable = [
    	'nombre_completo',
        'edad',
        'parentesco_id',
        'dependencia_economica',
        'factores_vulnerabilidad',
        'familiares_id',
        'victimas_directas_id',
    ];
}
