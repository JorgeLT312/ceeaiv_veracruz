<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaniosSufridos extends Model
{
    use HasFactory;
    protected $table = 'danios_sufridos';
    protected $fillable = [
        'clave',
        'nombre',
        'danios_sufridos_id'
    ];
}
