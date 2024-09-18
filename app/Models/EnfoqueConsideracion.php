<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueConsideracion extends Model
{
    use HasFactory;
    protected $table = 'enfoques_consideraciones';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
