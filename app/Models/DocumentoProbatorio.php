<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoProbatorio extends Model
{
    use HasFactory;
    protected $table = 'documentos_probatorios';
    protected $fillable = [
        'clave',
        'nombre'
    ];
}
