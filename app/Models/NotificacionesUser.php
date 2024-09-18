<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionesUser extends Model
{
    use HasFactory;
    protected $table = 'tbl_notificaciones_mm_users';
    protected $fillable = [
        'estatus',
        'generales_id',
        'users_id',
        'notificaciones_id'
    ];
}