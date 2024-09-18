<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadFederativa extends Model
{
    use HasFactory;
    protected $table = 'entidades_federativas';
    protected $fillable = [
        'clave',
        'nombre',
        'entidades_federativas_id'
    ];
}
