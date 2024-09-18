<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocProbatorioIdentidad extends Model
{
    use HasFactory;
    protected $table = 'identidad_probatorio_documentos';
    protected $fillable = [
        'clave',
        'nombre',
        'identidad_probatorio_documentos_id'
    ];
}
