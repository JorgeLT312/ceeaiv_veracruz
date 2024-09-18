<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueViolenciaMujer extends Model
{
    use HasFactory;
    protected $table = 'enfoques_violencia_mujeres';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
