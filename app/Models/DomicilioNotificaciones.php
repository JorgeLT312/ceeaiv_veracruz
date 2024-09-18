<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomicilioNotificaciones extends Model
{
    use HasFactory;
    protected $table = 'domicilio_notificaciones';
    protected $fillable = [
 		'calle',
        'no_exterior',
        'no_interior',
        'colonia',
        'codigo_postal',
        'localidad',
        'estado_civil',
        'escolaridad',
        'ocupacion_id',
        'organizacion_pertenece',
        'cual_organizacion_pertenece',
        'domicilio_notificaciones_id',
        'telefono',
        'lada',
        'extension',
        'territorios_id',
        'paises_id',
        'entidades_federativas_id',
        'municipios_id',
        'ocupaciones_id',
        'tipos_telefonos_id',
    ];
}
