<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoliosRev extends Model
{
    use HasFactory;
    protected $table = 'folios_rev';
    protected $fillable = [
        'folio',
        'anio',
    ];
}
