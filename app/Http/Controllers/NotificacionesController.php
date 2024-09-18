<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Notificaciones;
use App\Models\NotificacionesUser;
use App\Models\Role;
use App\Models\Area;
use App\Models\User;
use App\Enums\EstatusNotificacionesEnum;
use App\Enums\EtiquetaNotificacionesEnum;
use App\Enums\AreasEnum;
use App\Enums\RolesEnum;
use Carbon\Carbon;
use Auth;

class NotificacionesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->startDate.' 00:00:00')->toDateTimeString('second');
        $endDate = Carbon::parse($request->endDate.' 23:59:59')->toDateTimeString('second');
        if(empty($request->startDate)){
            $startDate = Carbon::now()->subMonth(1)->startOfDay()->toDateTimeString('second');
        }
        $notificaciones=NotificacionesUser::select(
            'tbl_notificaciones_mm_users.*',
            'generales.id',
            'generales.tipo_victimas_id',
            'notificaciones.descripcion',
            'notificaciones.rol',
            'notificaciones.area',
            'notificaciones.tipo',
            'users.name',
            'users.id as user_id',
            'victimas_directas.nombres',
            'victimas_directas.primer_apellido',
            'victimas_directas.segundo_apellido',
            'victimas_indirectas.nombres as indirecta_nombres',
            'victimas_indirectas.primer_apellido as indirecta_primer_apellido',
            'victimas_indirectas.segundo_apellido as indirecta_segundo_apellido',
            )
            ->join('notificaciones', 'notificaciones.notificaciones_id', '=', 'tbl_notificaciones_mm_users.notificaciones_id')
            ->join('users', 'users.id', '=', 'tbl_notificaciones_mm_users.users_id')
            ->join('generales', 'generales.id', '=', 'tbl_notificaciones_mm_users.generales_id')
            ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
            ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
            ->whereBetween('tbl_notificaciones_mm_users.updated_at', [$startDate, $endDate])
            ->where(function ($query) use ($request){
                $query->where('notificaciones.descripcion', 'like', "%$request->search%");
            })
            ->where('users_id',Auth::id())
            ->latest()
            ->paginate()
            ->withQueryString();

        $filters = $request->only(['search']);

        DB::table('tbl_notificaciones_mm_users')
                ->where('users_id', Auth::id())
                ->update(['estatus' => EstatusNotificacionesEnum::VISTO]);

        return Inertia::render("Notificaciones", compact(
                'filters',
                'notificaciones'
            ));
    }

    public function create()
    {
        // code...
    }

    public function store(RevRequest $request)
    {
        switch ($request->etiqueta) {
            
            
            
            
        }   
    }

    public function obtenerNotificaciones(Request $request)
    {
        $numeroNotificaciones=DB::table('tbl_notificaciones_mm_users')
            ->where('users_id',Auth::id())
            ->where('estatus',EstatusNotificacionesEnum::SIN_VER)
            ->where('activo_asignado',1)
            ->count();
        return $numeroNotificaciones;
    }
    

    public function edit(string $id)
    {
        // code...
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function destroy(string $id)
    {
        //
    }
}
