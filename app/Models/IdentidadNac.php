<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentidadNac extends Model
{
    use HasFactory;
    protected $table = 'territorios';
    protected $fillable = [
        'clave',
        'nombre',
        'territorios_id'
    ];
}
