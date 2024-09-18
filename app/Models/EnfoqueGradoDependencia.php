<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueGradoDependencia extends Model
{
    use HasFactory;
    protected $table = 'enfoque_grado_dependencia';
    protected $fillable = [
        'clave',
        'nombre',
        'enfoque_grado_dependencia_id'
    ];
}
