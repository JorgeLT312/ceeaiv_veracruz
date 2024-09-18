<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolacionDH extends Model
{
    use HasFactory;
    protected $table = 'violaciones';
    protected $fillable = [
        'clave',
        'nombre',
        'violaciones_id'
    ];
}
