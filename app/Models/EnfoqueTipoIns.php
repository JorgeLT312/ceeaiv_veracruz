<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnfoqueTipoIns extends Model
{
    use HasFactory;
    protected $table = 'enfoque_tipo_inst';
    protected $fillable = [
        'clave',
        'nombre',
        'enfoque_tipo_inst_id'
    ];
}
