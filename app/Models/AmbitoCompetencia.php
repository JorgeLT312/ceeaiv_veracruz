<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbitoCompetencia extends Model
{
    use HasFactory;
    protected $table = 'ambito_competencias';
    protected $fillable = [
        'clave',
        'nombre',
        'id'
    ];
}
