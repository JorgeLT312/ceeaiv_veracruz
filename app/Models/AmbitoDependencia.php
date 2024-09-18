<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbitoDependencia extends Model
{
    use HasFactory;
    protected $table = 'ambito_dependencias';
    protected $fillable = [
        'clave',
        'nombre',
        'ambito_dependencias_id'
    ];
}
