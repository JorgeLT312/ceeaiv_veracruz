<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoquePobIndigena extends Model
{
    use HasFactory;
    protected $table = 'enfoques_poblaciones_indigenas';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
