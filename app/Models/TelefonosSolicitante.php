<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelefonosSolicitante extends Model
{
    use HasFactory;
    protected $table = 'telefonos_solicitante';
    protected $fillable = [
        'id',
        'telefono',
        'solicitante_id'
    ];
}
