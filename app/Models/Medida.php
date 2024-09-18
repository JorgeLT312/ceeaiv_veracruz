<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    use HasFactory;
    protected $table = 'medidas';
    protected $fillable = [
        'nivel',
        'clave',
        'code',
        'nivel1',
        'nivel2',
        'nivel3',
        'nivel4',
        'nivel5',
    ];
}
