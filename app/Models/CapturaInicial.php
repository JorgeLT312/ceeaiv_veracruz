<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapturaInicial extends Model
{
    use HasFactory;
    protected $table = 'capturas_iniciales';
    protected $fillable = [
        'contacto_principal_nombres',
        'contacto_principal_primer_apellido',
        'contacto_principal_segundo_apellido',
        'contacto_principal_correo',
        'contacto_principal_telefono',
        'contacto_secundario_nombres',
        'contacto_secundario_primer_apellido',
        'contacto_secundario_segundo_apellido',
        'contacto_secundario_correo',
        'contacto_secundario_telefono',
        'generales_id',
        'victimas_directas_id',
        'victimas_indirectas_id',
    ];
}
