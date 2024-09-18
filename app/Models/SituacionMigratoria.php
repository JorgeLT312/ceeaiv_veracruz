<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituacionMigratoria extends Model
{
    use HasFactory;
    protected $table = 'situaciones_migratorias';
    protected $fillable = [
        'clave',
        'nombre',
        'situaciones_migratorias_id'
    ];
}
