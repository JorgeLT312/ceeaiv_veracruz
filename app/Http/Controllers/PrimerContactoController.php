<?php

namespace App\Http\Controllers;

use App\Models\TipoVictima;
use App\Models\VictimaDirecta;
use App\Models\VictimaIndirecta;
use App\Models\PrimerContacto;
use App\Models\CapturaInicial;
use App\Models\General;
use App\Models\Sexo;
use App\Models\IdentidadNac;
use App\Models\Delitos;
use App\Models\ViolacionDH;
use App\Models\Parentesco;
use App\Models\Municipios;
use App\Models\Pais;
use App\Models\EntidadFederativa;
use App\Models\User;
use App\Models\Solicitante;
use App\Models\HechosVictimizantes;
use App\Models\Familiares;
use App\Models\TelefonosSolicitante;
use App\Models\Ocupacion;
use App\Models\Folios;
use App\Models\RegistrosEstatales;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\PrimerContactoRequest;
use App\Http\Requests\SubirEscanerPCRequest;
use App\Enums\TipoVictimaEnum;
use App\Enums\NacionalidadEnum;
use App\Enums\NumeroPaso;
use App\Enums\FormatosEnum;
use Carbon\Carbon;
use App\Enums\EstatusAtencionEnum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\TextRun;
use App\Enums\AreasEnum;
use App\Enums\RolesEnum;
use App\Models\Notificaciones;
use App\Models\NotificacionesUser;
use App\Models\Role;
use App\Models\Area;
use App\Enums\EstatusNotificacionesEnum;
use App\Enums\EtiquetaNotificacionesEnum;
use Auth;


class PrimerContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->startDate.' 00:00:00')->toDateTimeString('second');
        $endDate = Carbon::parse($request->endDate.' 23:59:59')->toDateTimeString('second');
        if(empty($request->startDate)){
            $startDate = Carbon::now()->subMonth(1)->startOfDay()->toDateTimeString('second');
        }
        
        $capturas=General::select('generales.*',
            'victimas_directas.nombres',
            'victimas_directas.primer_apellido',
            'victimas_directas.segundo_apellido',
            'victimas_indirectas.nombres as indirecta_nombres',
            'victimas_indirectas.primer_apellido as indirecta_primer_apellido',
            'victimas_indirectas.segundo_apellido as indirecta_segundo_apellido',
            'tipo_victimas.nombre as nombre_tipo_victima',
            'pc_entrevistas.modalidad_atencion',
            'pc_entrevistas.registro_rev',
            'pc_entrevistas.updated_at as fecha_atencion'
            )
                ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
                ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
                ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
                ->join('pc_entrevistas', 'generales.id', '=', 'pc_entrevistas.generales_id')
                ->whereBetween('pc_entrevistas.updated_at', [$startDate, $endDate])
                /*->when($request->search, function($query, $search) {
                    $query->where('generales.no_rev', 'like', "%$search%")
                    ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                    ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                    ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                    ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                    ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                    ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%")
                    ->orWhere('tipo_victimas.nombre', 'like', "%$search%");
                })*/
                ->where(function ($query) use ($request){
                    $query->where('generales.no_rev', 'like', "%$request->search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                    TRIM(victimas_directas.primer_apellido), " ", 
                                    TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$request->search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                    TRIM(victimas_indirectas.primer_apellido), " ", 
                                    TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$request->search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$request->search%")
                        ->orWhere('tipo_victimas.nombre', 'like', "%$request->search%");
                })
                ->where(function ($query) {
                    $query->where('generales.estatus_atencion',EstatusAtencionEnum::PRIMER_CONTACTO)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::CAPTURA_INICIAL)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::ESPERANDO_APROBACION_REV)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::REV)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::NO_APROBADO)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::EN_ESPERA_RENAVI)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::RENAVI);
                })
                ->latest()
                ->paginate()
                ->withQueryString();

        $sexos = Sexo::all();
        $delitosProv = Delitos::orderBy('nombre','asc')->get();
        $violacionesDh = ViolacionDH::orderBy('nombre','asc')->get();
        $parentescos = Parentesco::orderBy('nombre','asc')->get();

        $nacionalidades = IdentidadNac::all();
        $paises = Pais::orderBy('nombre','asc')->get();
        $entidadesFederativas = EntidadFederativa::orderBy('name','asc')->get();
        $municipios = Municipios::orderBy('name','asc')->get();

        $tipoVictimas = TipoVictima::all();
        $psicologoCargo=User::where('role_id',3)->where('area_id',2)->get();
        $asesorJuridico=User::where('role_id',3)->where('area_id',4)->get();
        $ocupaciones=Ocupacion::orderBy('nombre_ocupacion')->get();
        $filters = $request->only(['search']);

        return Inertia::render('PrimerContacto', compact('sexos','nacionalidades','paises', 'entidadesFederativas', 'delitosProv','violacionesDh','parentescos','municipios','tipoVictimas', 'filters','capturas','psicologoCargo','asesorJuridico','ocupaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $generales_id)
    {
        $tipoVictima= DB::table('generales')->where('id',$generales_id)->first();
        $pc_entrevistas = DB::table('generales')
            ->where('generales.id',$generales_id)
            ->join('pc_entrevistas', 'pc_entrevistas.generales_id', '=', 'generales.id')
            ->join('datos_solicitantes', 'datos_solicitantes.generales_id', '=', 'generales.id')
            ->join('hechos_victimizantes', 'hechos_victimizantes.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_directas', 'victimas_directas.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_indirectas', 'victimas_indirectas.generales_id', '=', 'generales.id')
            ->join('domicilio_notificaciones', 'domicilio_notificaciones.generales_id', '=', 'generales.id')
            ->join('factores_vulnerabilidades', 'factores_vulnerabilidades.generales_id', '=', 'generales.id')
            ->select('pc_entrevistas.*', 'generales.*',
                'datos_solicitantes.fecha_nacimiento as solicitante_fecha_nacimiento',
                'datos_solicitantes.sexos_id as solicitantes_sexos_id',
                'datos_solicitantes.nombres as solicitantes_nombres',
                'datos_solicitantes.primer_apellido as solicitantes_primer_apellido',
                'datos_solicitantes.segundo_apellido as solicitantes_segundo_apellido',
                'datos_solicitantes.territorios_id as solicitantes_territorios_id',
                'datos_solicitantes.paises_id as solicitantes_paises_id',
                'datos_solicitantes.entidades_federativas_id as solicitantes_entidades_federativas_id',
                'datos_solicitantes.municipios_id as solicitantes_municipios_id',
                'datos_solicitantes.poblacion as solicitantes_poblacion',
                'hechos_victimizantes.territorios_id as hechos_territorios_id',
                'hechos_victimizantes.paises_id as hechos_paises_id',
                'hechos_victimizantes.entidades_federativas_id as hechos_entidades_federativas_id',
                'hechos_victimizantes.municipios_id as hechos_municipios_id',
                'hechos_victimizantes.poblacion as hechos_poblacion',
                'hechos_victimizantes.relato',
                //'hechos_victimizantes.delitos_id',
                //'hechos_victimizantes.violaciones_id',
                'victimas_directas.nombres as victimas_nombres',
                'victimas_directas.primer_apellido as victimas_primer_apellido',
                'victimas_directas.segundo_apellido as victimas_segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                'victimas_directas.delitos_id',
                'victimas_directas.violaciones_id',
                'victimas_directas.no_carpeta',
                'victimas_directas.fiscalia',
                'victimas_indirectas.nombres as victimas_indirectas_nombres',
                'victimas_indirectas.primer_apellido as victimas_indirectas_primer_apellido',
                'victimas_indirectas.segundo_apellido as victimas_nidirectas_segundo_apellido',
                'domicilio_notificaciones.territorios_id as notificaciones_territorios_id',
                'domicilio_notificaciones.paises_id as notificaciones_paises_id',
                'domicilio_notificaciones.entidades_federativas_id as notificaciones_entidades_federativas_id',
                'domicilio_notificaciones.municipios_id as notificaciones_municipios_id',
                'domicilio_notificaciones.calle',
                'domicilio_notificaciones.no_exterior',
                'domicilio_notificaciones.no_interior',
                'domicilio_notificaciones.colonia',
                'domicilio_notificaciones.codigo_postal',
                'domicilio_notificaciones.localidad',
                'domicilio_notificaciones.estado_civil',
                'domicilio_notificaciones.escolaridad',
                'domicilio_notificaciones.ocupaciones_id as notificaciones_ocupaciones_id',
                'domicilio_notificaciones.organizacion_pertenece',
                'domicilio_notificaciones.cual_organizacion_pertenece',
                'factores_vulnerabilidades.*'
                )->get()->first();

        return response()->json(['data'=>$pc_entrevistas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrimerContactoRequest $request, string $generales_id)
    {
        switch ($request->numeroPaso) {
            case NumeroPaso::PASO1:
                $this->guardarPaso1($request,$generales_id);
                break;
            case NumeroPaso::PASO2:
                $this->guardarPaso2($request,$generales_id);
                break;
            case NumeroPaso::PASO3:
                $this->guardarPaso3($request,$generales_id);
                break;
            case NumeroPaso::PASO4:
                $this->guardarPaso4($request,$generales_id);
                break;
            case NumeroPaso::PASO5:
                $this->guardarPaso5($request,$generales_id);
                break;
            case NumeroPaso::PASO6:
                $this->guardarPaso6($request,$generales_id);
                break;
            case NumeroPaso::PASO7:
                $this->guardarPaso7($request,$generales_id);
                break;
            case NumeroPaso::PASO8:
                $this->guardarPaso8($request,$generales_id);
                break;
            case NumeroPaso::PASO9:
                $this->guardarPaso9($request,$generales_id);
                break;
            case NumeroPaso::PASO10:
                $this->guardarPaso10($request,$generales_id);
                break;
            case NumeroPaso::PASO11:
                $this->guardarPaso11($request,$generales_id);
                break;
            case NumeroPaso::PASO12:
                $this->guardarPaso12($request,$generales_id);
                break;
            default:
                if ($request->registro_rev) {
                    DB::table('generales')
                            ->where('id', $generales_id)
                            ->update([
                                'estatus_atencion' => EstatusAtencionEnum::ESPERANDO_APROBACION_REV,
                                'created_by' => Auth::id()
                            ]);
                    //Inserta un registro en: Registro estatal
                    $ultimo_folio=Folios::get()->last();
                    //Verifica si no existe ningun folio o si es un año nuevo se reinicia los folios
                    if(empty($ultimo_folio) || date('Y') > $ultimo_folio->anio){
                        $folios = new Folios;
                        $folios->folio = 1;
                        $folios->anio = date('Y');
                        $folios->save();
                    }
                    else{
                        $folios = new Folios;
                        $folios->folio = $ultimo_folio->folio+1;
                        $folios->anio = date('Y');
                        $folios->save();
                    }

                    //Se agregan 0 faltantes
                    (strlen($folios->folio) < 5) ? $folio_cast=str_pad($folios->folio, 5, "0", STR_PAD_LEFT) : $folio_cast=$folios->folio;
                    $esRev=RegistrosEstatales::where('generales_id',$generales_id)->first();
                    if(empty($esRev)){
                        $registroEstatal = new RegistrosEstatales;
                        $registroEstatal->folio_expediente = 'CEEAIV-REG/'.$folio_cast.'/'.$folios->anio;
                        $registroEstatal->generales_id = $generales_id;
                        $registroEstatal->fecha_atencion = Carbon::now();
                        $registroEstatal->save();
                    }

                }
                else{
                    DB::table('generales')
                            ->where('id', $generales_id)
                            ->update([
                                'estatus_atencion' => EstatusAtencionEnum::PRIMER_CONTACTO,
                                'created_by' => Auth::id()
                        ]);
                }
                $this->guardarPaso1($request,$generales_id);
                $this->guardarPaso2($request,$generales_id);
                $this->guardarPaso3($request,$generales_id);
                $this->guardarPaso4($request,$generales_id);
                $this->guardarPaso5($request,$generales_id);
                $this->guardarPaso6($request,$generales_id);
                $this->guardarPaso7($request,$generales_id);
                $this->guardarPaso8($request,$generales_id);
                $this->guardarPaso9($request,$generales_id);
                $this->guardarPaso10($request,$generales_id);
                $this->guardarPaso11($request,$generales_id);
                $this->guardarPaso12($request,$generales_id);
                $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::lleno_entrevista_pc,null);
                break;
        }
    }

    public function crearNotificacion($generales_id,$etiquetaNotificacion,$asignado_id)
    {
        switch ($etiquetaNotificacion) {
            case EtiquetaNotificacionesEnum::lleno_entrevista_pc:
                $role_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::PC)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::lleno_entrevista_pc)->first();
                $usuarios=User::where('role_id',$role_id)->where('area_id',$area_id)->get();
                foreach ($usuarios as $usuario) {
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$usuario->id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->first();
                    if (empty($usuario_existe)) {
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $usuario->id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
                break;
            case EtiquetaNotificacionesEnum::solicito_asesoria:
                $role_superior_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                //$role_operativo_id=Role::where('name',RolesEnum::OPERATIVO)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::JURIDICO)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::solicito_asesoria)->first();
                //crea notificacion para todos los usuarios role: superior y area: juridico
                $usuarios=User::where('role_id',$role_superior_id)->where('area_id',$area_id)->get();
                foreach ($usuarios as $usuario) {
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$usuario->id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->first();
                    if (empty($usuario_existe)) {
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $usuario->id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
                //crea notificación para juridico asignado
                if (!empty($asignado_id)) {
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$asignado_id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->first();
                    if (empty($usuario_existe)) {
                        $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::solicito_asesoria_asignado)->first();
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $asignado_id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
                break;
            case EtiquetaNotificacionesEnum::solicito_psicologo:
                $role_superior_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                //$role_operativo_id=Role::where('nombre',RolesEnum::OPERATIVO)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::PC)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::solicito_psicologo)->first();
                //Crea notificaciones para los usuarios rol: superior area pc
                $usuarios=User::where('role_id',$role_superior_id)->where('area_id',$area_id)->get();
                foreach ($usuarios as $usuario) { 
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$usuario->id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->first();
                    if(empty($usuario_existe)) {
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $usuario->id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
                //crea notificacion para psicologo asignado
                if (!empty($asignado_id)) {
                    $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::solicito_psicologo_asignado)->first();
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$asignado_id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->where('activo_asignado',1)
                                    ->first();
                    if (empty($usuario_existe)) {
                        NotificacionesUser::where('generales_id', $generales_id)->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                           ->update([
                               'activo_asignado' => 0
                            ]);
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $asignado_id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
            break;
            case EtiquetaNotificacionesEnum::solicito_registro_rev:
                $role_superior_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                $role_operativo_id=Role::where('name',RolesEnum::OPERATIVO)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::REV)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::solicito_registro_rev_superior)->first();

                $usuarios_superior=User::where('role_id',$role_superior_id)->where('area_id',$area_id)->get();
                foreach ($usuarios_superior as $usuario) {
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$usuario->id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->first();
                    if (empty($usuario_existe)) {
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $usuario->id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::solicito_registro_rev_operativo)->first();
                $usuarios_operativo=User::where('role_id',$role_operativo_id)->where('area_id',$area_id)->get();
                foreach ($usuarios_superior as $usuario) {
                    $usuario_existe=NotificacionesUser::where('generales_id',$generales_id)
                                    ->where('users_id',$usuario->id)
                                    ->where('notificaciones_id',$notificaciones_id->notificaciones_id)
                                    ->first();
                    if (empty($usuario_existe)) {
                        $notificaciones = new NotificacionesUser;
                        $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                        $notificaciones->users_id = $usuario->id;
                        $notificaciones->generales_id = $generales_id;
                        $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                        $notificaciones->activo_asignado = 1;
                        $notificaciones->save();
                    }
                }
            break;
        }                                
    }


    public function guardarPaso1($request,$generales_id)
    {
        if ($request->modalidad_atencion) {
            DB::table('pc_entrevistas')
                ->where('generales_id', $generales_id)
                ->update(['modalidad_atencion' => $request->modalidad_atencion]);
            if ($request->colaboracion) {
                DB::table('pc_entrevistas')
                ->where('generales_id', $generales_id)
                ->update([
                    'colaboracion' => 1,
                    'institucion' => $request->institucion,
                    'no_oficio' => $request->no_oficio
                ]);
            }
        }

        return true;
    }

    public function guardarPaso2($request,$generales_id)
    {
        $solicitante = DB::table('datos_solicitantes')
            ->where('generales_id', $generales_id);
        $solicitante->update([
            'nombres' => ($request->solicitante_nombres)?$request->solicitante_nombres:'',
            'primer_apellido' => ($request->solicitante_primer_apellido)?$request->solicitante_primer_apellido:'',
            'segundo_apellido' => ($request->solicitante_segundo_apellido)?$request->solicitante_segundo_apellido:'',
            'sexos_id' => $request->sexos_id,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);
        switch ($request->territorios_id) {
            case NacionalidadEnum::MEXICANA:
                $solicitante
                ->update([
                    'territorios_id' => $request->territorios_id,
                    'entidades_federativas_id' => $request->entidades_federativas_id,
                    'municipios_id' => $request->municipios_id,
                    'poblacion' => $request->poblacion
                ]);
                break;
            case NacionalidadEnum::EXTRANJERA:
                $solicitante
                ->update([
                    'territorios_id' => $request->territorios_id,
                    'paises_id' => $request->paises_id
                ]);
                break;
            case NacionalidadEnum::NINGUNA:
                $solicitante
                ->update([
                    'territorios_id' => $request->territorios_id,
                ]);
                break;
        }

        if (!empty($request->telefonos)) {
            TelefonosSolicitante::where('solicitante_id', $solicitante->pluck('datos_solicitantes_id')->first())->delete();
            foreach ($request->telefonos as $telefono) {
                if(!empty($telefono)){
                    $telefonos = new TelefonosSolicitante;
                    $telefonos->telefono = $telefono;
                    $telefonos->solicitante_id = $solicitante->pluck('datos_solicitantes_id')->first();
                    $telefonos->save();
                }
            }
        }

        return true;
    }

    public function guardarPaso3($request,$generales_id)
    {
        $hechosVictimizantes = DB::table('hechos_victimizantes')->where('generales_id',$generales_id);
        $hechosVictimizantes->update([
            'relato' => $request->hechos_relato
        ]);
        switch ($request->hechos_territorios_id) {
            case NacionalidadEnum::MEXICANA:
                $hechosVictimizantes
                ->update([
                    'territorios_id' => $request->hechos_territorios_id,
                    'entidades_federativas_id' => $request->hechos_entidades_federativas_id,
                    'municipios_id' => $request->hechos_municipios_id,
                    'poblacion' => $request->hechos_poblacion
                ]);
                break;
            case NacionalidadEnum::EXTRANJERA:
                $hechosVictimizantes
                ->update([
                    'territorios_id' => $request->hechos_territorios_id,
                    'paises_id' => $request->hechos_paises_id
                ]);
                break;
            case NacionalidadEnum::NINGUNA:
                $hechosVictimizantes
                ->update([
                    'territorios_id' => $request->hechos_territorios_id,
                ]);
                break;
        }
        return true;
    }

    public function guardarPaso4($request,$generales_id)
    {
        DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->update([
                'es_competencia_ceeaiv' => ($request->es_competencia_ceeaiv) ? 1 : 0,
                'orientacion_brindada' => $request->orientacion_brindada,
            ]);
        return true;
    }

    public function guardarPaso5($request,$generales_id)
    {
        $id_ulitmo_ocupacion=0;

        $esVDirecta = DB::table('victimas_directas')
            ->where('generales_id', $generales_id)->first();
        if($esVDirecta){
            $VDirecta = DB::table('victimas_directas')
            ->where('generales_id', $generales_id)
            ->update([
                'nombres' => $request->victima_nombres,
                'primer_apellido' => $request->victima_primer_apellido,
                'segundo_apellido' => $request->victima_segundo_apellido,
                'fecha_nacimiento' => $request->victima_fecha_nacimiento,
                'no_carpeta' => $request->no_carpeta,
                'fiscalia' => $request->fiscalia,
                'ocupaciones_id' => ($id_ulitmo_ocupacion==0)?$request->victima_ocupacion_id:$id_ulitmo_ocupacion,
                'delitos_id' => $request->delitos_id,
                'violaciones_id' => $request->violaciones_id,
            ]);
            $victimaDirecta_id=$esVDirecta->victimas_directas_id;
        }
        else{
            if ($request->victima_nombres || $request->victima_primer_apellido || $request->victima_segundo_apellido) {
                $victimaDirecta = new VictimaDirecta;
                $victimaDirecta->nombres = $request->victima_nombres;
                $victimaDirecta->primer_apellido = $request->victima_primer_apellido;
                $victimaDirecta->segundo_apellido = $request->victima_segundo_apellido;
                $victimaDirecta->fecha_nacimiento = $request->victima_fecha_nacimiento;
                $victimaDirecta->delitos_id = $request->delitos_id;
                $victimaDirecta->violaciones_id = $request->violaciones_id;
                $victimaDirecta->no_carpeta = $request->no_carpeta;
                $victimaDirecta->fiscalia = $request->fiscalia;
                $victimaDirecta->generales_id = $generales_id;
                $victimaDirecta->save();
                $victimaDirecta_id=$victimaDirecta->victimas_directas_id;
            }

        }
        return true;
    }

    public function guardarPaso6($request,$generales_id)
    {
        Familiares::where('generales_id', $generales_id)->delete();
        foreach ($request->familiares as $familiar) {
            if(!empty($familiar)){
                $familia = new Familiares;
                $familia->nombre_completo = $familiar['nombre_completo'];
                $familia->edad = $familiar['edad'];
                $familia->parentesco_id = $familiar['parentesco_id'];
                $familia->dependencia_economica = $familiar['dependencia_economica'];
                $familia->factores_vulnerabilidad = $familiar['factores_vulnerabilidad'];
                $familia->generales_id = $generales_id;
                $familia->save();
            }
        }
        DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->update([
                'familiar_observaciones' => $request->familiar_observaciones,
            ]);
        return true;
    }

    public function guardarPaso7($request,$generales_id)
    {
        $domicilioNotificaciones = DB::table('domicilio_notificaciones')
            ->where('generales_id', $generales_id);
        $domicilioNotificaciones->update([
                'calle' => $request->calle,
                'no_exterior' => $request->no_exterior,
                'no_interior' => $request->no_interior,
                'colonia' => $request->colonia,
                'codigo_postal' => $request->codigo_postal,
                'localidad' => $request->localidad,
                'estado_civil' => $request->estado_civil,
                'escolaridad' => $request->escolaridad,
                'ocupaciones_id' => $request->ocupacion,
                'organizacion_pertenece' => $request->organizacion_pertenece ? 1 : 0,
                'cual_organizacion_pertenece' => $request->cual_organizacion_pertenece
            ]);
        switch ($request->notificaciones_territorios_id) {
            case NacionalidadEnum::MEXICANA:
                $domicilioNotificaciones->update([
                    'territorios_id' => $request->notificaciones_territorios_id,
                    'entidades_federativas_id' => $request->notificaciones_entidades_federativas_id,
                    'municipios_id' => $request->notificaciones_municipios_id
                ]);
                break;
            case NacionalidadEnum::EXTRANJERA:
                $domicilioNotificaciones->update([
                    'territorios_id' => $request->notificaciones_territorios_id,
                    'paises_id' => $request->notificaciones_paises_id,
                ]);
                break;
            case NacionalidadEnum::NINGUNA:
                $domicilioNotificaciones
                ->update([
                    'territorios_id' => $request->notificaciones_territorios_id,
                ]);
                break;
        }
        return true;
    }

    public function guardarPaso8($request,$generales_id)
    {
        DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->update([
                'actividad_ingreso' => $request->actividad_ingreso,
                'cual_actividad_ingreso' => $request->cual_actividad_ingreso,
                'programa_social' => $request->programa_social,
                'cual_programa_social' => $request->cual_programa_social,
                'apoyo_federacion_estado' => $request->apoyo_federacion_estado,
                'cual_apoyo_federacion_estado' => $request->cual_apoyo_federacion_estado
            ]);
        return true;
    }

    public function guardarPaso9($request,$generales_id)
    {
        DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->update([
                'afectacion_salud' => $request->afectacion_salud,
                'cual_afectacion_salud' => $request->cual_afectacion_salud,
                'servicio_atencion_medica' => $request->servicio_atencion_medica,
                'cual_servicio_atencion_medica' => $request->cual_servicio_atencion_medica
            ]);
        return true;
    }

    public function guardarPaso10($request,$generales_id)
    {
        DB::table('factores_vulnerabilidades')
            ->where('generales_id', $generales_id)
            ->update([
                'es_adolescente' => $request->es_adolescente,
                'es_adulto_mayor' => $request->es_adulto_mayor,
                'es_lgbttti' => $request->es_lgbttti,
                'es_discapacitado' => $request->es_discapacitado,
                'es_situacion_calle' => $request->es_situacion_calle,
                'es_migrante' => $request->es_migrante,
                'es_desplazado_por_hecho' => $request->es_desplazado_por_hecho,
                'es_periodista' => $request->es_periodista,
                'es_defensor_ddhh' => $request->es_defensor_ddhh,
                'es_violencia_genero' => $request->es_violencia_genero,
                'es_trastorno_mental' => $request->es_trastorno_mental,
                'es_vih' => $request->es_vih,
                'observaciones' => $request->vulnerabilidades_observaciones,
            ]);
        return true;
    }

    public function guardarPaso11($request,$generales_id)
    {
        DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->update([
                'gestion_medica' => $request->gestion_medica,
                'gestion_medica_necesidades' => $request->gestion_medica_necesidades,
                'gestion_medica_derivacion' => $request->gestion_medica_derivacion,
                'gestion_social' => $request->gestion_social,
                'gestion_social_necesidades' => $request->gestion_social_necesidades,
                'gestion_social_derivacion' => $request->gestion_social_derivacion,
                'gestion_social_num_seguro_social' => $request->gestion_social_num_seguro_social,
                'atencion_psicologica' => $request->atencion_psicologica,
                'atencion_psicologica_necesidades' => $request->atencion_psicologica_necesidades,
                'atencion_psicologica_derivacion' => $request->atencion_psicologica_derivacion,
                'atencion_psicologica_es_interna' => $request->atencion_psicologica_es_interna,
                'atencion_psicologica_psicologo_id' => $request->atencion_psicologica_psicologo_id,
                'asesoria_juridica' => $request->asesoria_juridica,
                'asesoria_juridica_necesidades' => $request->asesoria_juridica_necesidades,
                'asesoria_juridica_derivacion' => $request->asesoria_juridica_derivacion,
                'asesoria_juridica_opciones' => $request->asesoria_juridica_opciones,
                'asesoria_juridica_opciones_otros' => $request->asesoria_juridica_opciones_otros,
                'asesoria_juridica_asesor_id' => $request->asesoria_juridica_asesor_id,
                'solicitudes_ayuda' => $request->solicitudes_ayuda,
                'solicitudes_ayuda_opciones' => $request->solicitudes_ayuda_opciones,
            ]);
        if ($request->asesoria_juridica==1) {
            $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::solicito_asesoria,$request->asesoria_juridica_asesor_id);
        }
        if ($request->atencion_psicologica==1) {
            $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::solicito_psicologo,$request->atencion_psicologica_psicologo_id);
        }
        return true;
    }

    public function guardarPaso12($request,$generales_id)
    {
        DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->update([
                'registro_rev' => ($request->registro_rev) ? 1 : 0,
                'ejerce_custodia_rev' => ($request->ejerce_custodia_rev) ? 1 : 0,
                'quien_ejerce_custodia_rev' => $request->quien_ejerce_custodia_rev,
                'observaciones_rev' => $request->observaciones_rev,
                'como_se_entero_ceeaiv' => $request->como_se_entero_ceeaiv,
            ]);
        if ($request->registro_rev==1) {
            $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::solicito_registro_rev,null);
        }
        return true;
    }

    public function subirArchivo(SubirEscanerPCRequest $request){
        if(!empty($request->file)){
            $filename = 'Escaner_victima_'.$request->generales_id.'.pdf';
            Storage::disk('public')->put('escaner/'.$filename, file_get_contents($request->file));
            DB::table('pc_entrevistas')
            ->where('generales_id', $request->generales_id)
            ->update([
                'url_escaner' => 'storage/escaner/'.$filename,
            ]);
        }
        return to_route('contacto.index');
    }
    public function verificarArchivo($generales_id)
        {
            try {
        $existingUrl = DB::table('pc_entrevistas')
            ->where('generales_id', $generales_id)
            ->value('url_escaner');

        if (!empty($existingUrl)) {
            return response()->json(['url' => $existingUrl]);
        } else {
            return response()->json(['url' => null]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al verificar la URL'], 500);
    }
}




    public function telefonos($generales_id)
    {
        $telefonos = DB::table('telefonos_solicitante')
            ->select('telefonos_solicitante.*')
            ->join('datos_solicitantes','telefonos_solicitante.solicitante_id','=','datos_solicitantes.datos_solicitantes_id')
            ->where('generales_id',$generales_id)
            ->get();
        return response()->json(['telefonos'=>$telefonos]);
    }

    public function familiares($generales_id)
    {
        $familiares = DB::table('familiares')->where('generales_id',$generales_id)->get();
        return response()->json(['familiares'=>$familiares]);
    }

    public function store_ocupacion(Request $request)
    {
        $validated = $request->validate([
            'nombre_ocupacion' => 'required|unique:ocupaciones'
        ]);

        DB::table('ocupaciones')->insert(["nombre_ocupacion"=>$request->nombre_ocupacion]);
    }

    public function imprimirPrimerContacto($tipo_formato,$generales_id)
    {
        $pc_entrevistas = DB::table('generales')
            ->where('generales.id',$generales_id)
            ->join('pc_entrevistas', 'pc_entrevistas.generales_id', '=', 'generales.id')
            ->join('datos_solicitantes', 'datos_solicitantes.generales_id', '=', 'generales.id')
            ->join('hechos_victimizantes', 'hechos_victimizantes.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_directas', 'victimas_directas.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_indirectas', 'victimas_indirectas.generales_id', '=', 'generales.id')
            ->leftJoin('domicilio_notificaciones', 'domicilio_notificaciones.generales_id', '=', 'generales.id')
            ->join('factores_vulnerabilidades', 'factores_vulnerabilidades.generales_id', '=', 'generales.id')
            ->leftJoin('sexos', 'datos_solicitantes.sexos_id', '=', 'sexos.sexos_id')
            ->leftJoin('municipios', 'datos_solicitantes.municipios_id', '=', 'municipios.municipios_id')
            ->leftJoin('delitos', 'delitos.delitos_id', '=', 'hechos_victimizantes.delitos_id')
            ->leftJoin('violaciones', 'violaciones.violaciones_id', '=', 'hechos_victimizantes.violaciones_id')
            ->select('pc_entrevistas.*', 'generales.*',
                'sexos.nombre as solicitantes_sexo',
                'municipios.name as solicitante_municipios',
                'delitos.nombre as delito_ddhh',
                'violaciones.nombre as violaciones_ddhh',
                'datos_solicitantes.fecha_nacimiento as solicitante_fecha_nacimiento',
                'datos_solicitantes.sexos_id as solicitantes_sexos_id',
                'datos_solicitantes.nombres as solicitantes_nombres',
                'datos_solicitantes.primer_apellido as solicitantes_primer_apellido',
                'datos_solicitantes.segundo_apellido as solicitantes_segundo_apellido',
                'datos_solicitantes.territorios_id as solicitantes_territorios_id',
                'datos_solicitantes.paises_id as solicitantes_paises_id',
                'datos_solicitantes.entidades_federativas_id as solicitantes_entidades_federativas_id',
                'datos_solicitantes.municipios_id as solicitantes_municipios_id',
                'datos_solicitantes.poblacion as solicitantes_poblacion',
                'datos_solicitantes.datos_solicitantes_id',
                'hechos_victimizantes.territorios_id as hechos_territorios_id',
                'hechos_victimizantes.paises_id as hechos_paises_id',
                'hechos_victimizantes.entidades_federativas_id as hechos_entidades_federativas_id',
                'hechos_victimizantes.municipios_id as hechos_municipios_id',
                'hechos_victimizantes.poblacion as hechos_poblacion',
                'hechos_victimizantes.relato',
                'victimas_directas.nombres as victimas_nombres',
                'victimas_directas.primer_apellido as victimas_primer_apellido',
                'victimas_directas.segundo_apellido as victimas_segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                'hechos_victimizantes.delitos_id',
                'hechos_victimizantes.violaciones_id',
                'victimas_directas.no_carpeta',
                'victimas_directas.fiscalia',
                'victimas_indirectas.nombres as victimas_indirectas_nombres',
                'victimas_indirectas.primer_apellido as victimas_indirectas_primer_apellido',
                'victimas_indirectas.segundo_apellido as victimas_nidirectas_segundo_apellido',
                'domicilio_notificaciones.territorios_id as notificaciones_territorios_id',
                'domicilio_notificaciones.paises_id as notificaciones_paises_id',
                'domicilio_notificaciones.entidades_federativas_id as notificaciones_entidades_federativas_id',
                'domicilio_notificaciones.municipios_id as notificaciones_municipios_id',
                'domicilio_notificaciones.calle as notificaciones_calle',
                'domicilio_notificaciones.no_exterior as notificaciones_no_exterior',
                'domicilio_notificaciones.no_interior as notificaciones_no_interior',
                'domicilio_notificaciones.colonia as notificaciones_colonia',
                'domicilio_notificaciones.codigo_postal as notificaciones_codigo_postal',
                'domicilio_notificaciones.localidad as notificaciones_localidad',
                'domicilio_notificaciones.estado_civil as notificaciones_estado_civil',
                'domicilio_notificaciones.escolaridad as notificaciones_escolaridad',
                'domicilio_notificaciones.ocupaciones_id as notificaciones_ocupaciones_id',
                'domicilio_notificaciones.organizacion_pertenece',
                'domicilio_notificaciones.cual_organizacion_pertenece',
                'factores_vulnerabilidades.*'
                )->first();
        $familiares = DB::table('familiares')->select('familiares.*','parentescos.nombre as nombre_parentesco')
        ->join('parentescos','parentescos.clave','=','familiares.parentesco_id')
        ->where('generales_id',$generales_id)->get();

        $observaciones_vulnerabilidad = DB::table('factores_vulnerabilidades')
        ->where('generales_id',$generales_id)->first();
        $notificaciones_lugar=DB::table('domicilio_notificaciones')->select('domicilio_notificaciones.*','entidades_federativas.name as notificaciones_entidad_federativa','municipios.name as notificaciones_municipio','territorios.nombre as notificaciones_nacionalidad',
            'ocupaciones.nombre_ocupacion as notificaciones_ocupacion')
        ->leftJoin('entidades_federativas','entidades_federativas.entidades_federativas_id','=','domicilio_notificaciones.entidades_federativas_id')
        ->leftJoin('municipios','municipios.municipios_id','=','domicilio_notificaciones.municipios_id')
        ->leftJoin('territorios','territorios.territorios_id','=','domicilio_notificaciones.territorios_id')
        ->leftJoin('ocupaciones','ocupaciones.ocupaciones_id','=','domicilio_notificaciones.ocupaciones_id')
        ->where('generales_id',$generales_id)->first();
        $ocupacion_victimaD = DB::table('ocupaciones')
        ->where('ocupaciones_id',$pc_entrevistas->victimas_ocupaciones_id)->first();
        $telefonos = TelefonosSolicitante::where('solicitante_id',$pc_entrevistas->datos_solicitantes_id)->select('telefono')->get();
        $telefonos_solicitante='';
        foreach ($telefonos as $telefono) {
            $telefonos_solicitante=$telefonos_solicitante.' / '.$telefono->telefono;
        }
        $marca='✓';
        $municipio_atencion='Xalapa';
        $edad_solicitante=Carbon::createFromDate($pc_entrevistas->solicitante_fecha_nacimiento)->age;
        $edad_victima=Carbon::createFromDate($pc_entrevistas->victimas_fecha_nacimiento)->age;
        $date=Carbon::parse($pc_entrevistas->created_at, 'UTC');
        $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
        $fecha = Carbon::parse($pc_entrevistas->created_at);
        $mes = $meses[($fecha->format('n')) - 1];
        switch ($tipo_formato) {
            case FormatosEnum::PRIMER_CONTACTO:
                try {
                    $template = new TemplateProcessor(resource_path('primer_contacto.docx'));
                    $template->setValue('colaboracion',($pc_entrevistas->colaboracion==1)?$marca:'');
                    $template->setValue('modalidad_atencion',$pc_entrevistas->modalidad_atencion);
                    $template->setValue('institucion',$pc_entrevistas->institucion);
                    $template->setValue('no_oficio',$pc_entrevistas->no_oficio);
                    $template->setValue('nombres_solicitante',($pc_entrevistas->solicitantes_nombres) ? $pc_entrevistas->solicitantes_nombres : '');
                    $template->setValue('primer_apellido_solicitante',$pc_entrevistas->solicitantes_primer_apellido);
                    $template->setValue('segundo_apellido_solicitante',$pc_entrevistas->solicitantes_segundo_apellido);
                    $template->setValue('solicitantes_sexo',$pc_entrevistas->solicitantes_sexo);
                    $template->setValue('municipio_solicitante',$pc_entrevistas->solicitante_municipios);
                    $template->setValue('municipio_atencion',$municipio_atencion);
                    $template->setValue('telefono',$telefonos_solicitante);
                    $template->setValue('fecha',$date->format('d').' '.$mes.' '.$date->format('Y').' '.$date->format('H:i A'));
                    $template->setValue('delito_ddhh',$pc_entrevistas->delito_ddhh.' / '.$pc_entrevistas->violaciones_ddhh);
                    $template->setValue('no_carpeta',$pc_entrevistas->no_carpeta);
                    $template->setValue('fiscalia',$pc_entrevistas->fiscalia);
                    $template->setValue('edad_solicitante',$edad_solicitante);
                    $template->setValue('observaciones_familiar',$pc_entrevistas->familiar_observaciones);
                    //Notificaciones
                    $template->setValue('notificaciones_calle',$pc_entrevistas->notificaciones_calle);
                    $template->setValue('notificaciones_no_exterior',$pc_entrevistas->notificaciones_no_exterior);
                    $template->setValue('notificaciones_no_interior',$pc_entrevistas->notificaciones_no_interior);
                    $template->setValue('notificaciones_colonia',$pc_entrevistas->notificaciones_colonia);
                    $template->setValue('notificaciones_codigo_postal',$pc_entrevistas->notificaciones_codigo_postal);
                    $template->setValue('notificaciones_localidad',$pc_entrevistas->notificaciones_localidad);
                    $template->setValue('notificaciones_municipios',$generales_id);
                    $template->setValue('notificaciones_estado_civil',$pc_entrevistas->notificaciones_estado_civil);
                    $template->setValue('notificaciones_escolaridad',$pc_entrevistas->notificaciones_escolaridad);
                    $template->setValue('notificaciones_municipio',$notificaciones_lugar->notificaciones_municipio);
                    $template->setValue('notificaciones_entidad',$notificaciones_lugar->notificaciones_entidad_federativa);
                    $template->setValue('notificaciones_nacionalidad',$notificaciones_lugar->notificaciones_nacionalidad);
                    $template->setValue('notificaciones_ocupacion',$notificaciones_lugar->notificaciones_ocupacion);
                    $template->setValue('es_organizacion',($pc_entrevistas->organizacion_pertenece==1)?$marca:'');
                    $template->setValue('no_es_organizacion',($pc_entrevistas->organizacion_pertenece==0)?$marca:'');
                    $template->setValue('cual_organizacion',$pc_entrevistas->cual_organizacion_pertenece);
                    $template->setValue('ocupacion',$generales_id);
                    $template->setValue('cual_organizacion_sociedad',$generales_id);
                    $template->setValue('relato',$pc_entrevistas->relato);
                    $template->setValue('victimas_nombres',$pc_entrevistas->victimas_nombres);
                    $template->setValue('victimas_primer_apellido',$pc_entrevistas->victimas_primer_apellido);
                    $template->setValue('victimas_segundo_apellido',$pc_entrevistas->victimas_segundo_apellido);
                    $template->setValue('edad_victimD',$edad_victima);
                    $template->setValue('ocupacion_victimaD',($ocupacion_victimaD)?$ocupacion_victimaD->nombre_ocupacion:'');

                    //Es competencia
                    $template->setValue('es_competencia_ceeaiv',($pc_entrevistas->es_competencia_ceeaiv==1)?$marca:'');
                    $template->setValue('no_es_competencia_ceeaiv',($pc_entrevistas->es_competencia_ceeaiv==0)?$marca:'');
                    $template->setValue('orientacion_brindada',$pc_entrevistas->orientacion_brindada);

                    $template->setValue('actividad_ingreso',($pc_entrevistas->actividad_ingreso==1)?$marca:'');
                    $template->setValue('no_actividad_ingreso',($pc_entrevistas->actividad_ingreso==0)?$marca:'');
                    $template->setValue('cual_actividad_ingreso',$pc_entrevistas->cual_actividad_ingreso);

                    $template->setValue('programa_social',($pc_entrevistas->programa_social==1)?$marca:'');
                    $template->setValue('no_programa_social',($pc_entrevistas->programa_social==0)?$marca:'');
                    $template->setValue('cual_programa_social',$pc_entrevistas->cual_programa_social);

                    $template->setValue('apoyo_federacion_estado',($pc_entrevistas->apoyo_federacion_estado==1)?$marca:'');
                    $template->setValue('no_apoyo_federacion_estado',($pc_entrevistas->apoyo_federacion_estado==0)?$marca:'');
                    $template->setValue('cual_apoyo_federacion_estado',$pc_entrevistas->cual_apoyo_federacion_estado);

                    $template->setValue('apoyo_federacion_estado',($pc_entrevistas->apoyo_federacion_estado==1)?$marca:'');
                    $template->setValue('no_apoyo_federacion_estado',($pc_entrevistas->apoyo_federacion_estado==0)?$marca:'');
                    $template->setValue('cual_apoyo_federacion_estado',$pc_entrevistas->cual_apoyo_federacion_estado);

                    $template->setValue('esDirecta',($pc_entrevistas->apoyo_federacion_estado==1)?$marca:'');
                    $template->setValue('esIndirecta',($pc_entrevistas->apoyo_federacion_estado==0)?$marca:'');

                    $template->setValue('afectacion_salud',($pc_entrevistas->afectacion_salud==1)?$marca:'');
                    $template->setValue('no_afectacion_salud',($pc_entrevistas->afectacion_salud==0)?$marca:'');
                    $template->setValue('cual_afectacion_salud',$pc_entrevistas->cual_afectacion_salud);

                    $template->setValue('atencion_medica',($pc_entrevistas->servicio_atencion_medica==1)?$marca:'');
                    $template->setValue('no_atencion_medica',($pc_entrevistas->servicio_atencion_medica==0)?$marca:'');
                    $template->setValue('cual_atencion_medica',$pc_entrevistas->cual_servicio_atencion_medica);

                    //Factores vulnerabilidad
                    $template->setValue('es_adolecente',($pc_entrevistas->es_adolescente==1)?$marca:'');
                    $template->setValue('no_es_adolecente',($pc_entrevistas->es_adolescente==0)?$marca:'');

                    $template->setValue('es_adulto_mayor',($pc_entrevistas->es_adulto_mayor==1)?$marca:'');
                    $template->setValue('no_es_adulto_mayor',($pc_entrevistas->es_adulto_mayor==0)?$marca:'');

                    $template->setValue('es_lgbtti',($pc_entrevistas->es_lgbttti==1)?$marca:'');
                    $template->setValue('no_lgbtii',($pc_entrevistas->es_lgbttti==0)?$marca:'');

                    $template->setValue('es_discapcitado',($pc_entrevistas->es_discapacitado==1)?$marca:'');
                    $template->setValue('no_es_discapacitado',($pc_entrevistas->es_discapacitado==0)?$marca:'');

                    $template->setValue('es_situacion_calle',($pc_entrevistas->es_situacion_calle==1)?$marca:'');
                    $template->setValue('no_es_situacion_calle',($pc_entrevistas->es_situacion_calle==0)?$marca:'');

                    $template->setValue('es_migrante',($pc_entrevistas->es_migrante==1)?$marca:'');
                    $template->setValue('no_es_migrante',($pc_entrevistas->es_migrante==0)?$marca:'');

                    $template->setValue('es_poblacion_indigena',($pc_entrevistas->pertenece_poblacion_indigena==1)?$marca:'');
                    $template->setValue('no_es_poblacion_indigena',($pc_entrevistas->pertenece_poblacion_indigena==0)?$marca:'');

                    $template->setValue('es_desplazamiento',($pc_entrevistas->es_desplazado_por_hecho==1)?$marca:'');
                    $template->setValue('no_es_desplazamiento',($pc_entrevistas->es_desplazado_por_hecho==0)?$marca:'');

                    $template->setValue('es_periodista',($pc_entrevistas->es_periodista==1)?$marca:'');
                    $template->setValue('no_es_periodista',($pc_entrevistas->es_periodista==0)?$marca:'');

                    $template->setValue('es_defensor',($pc_entrevistas->es_defensor_ddhh==1)?$marca:'');
                    $template->setValue('no_es_defensor',($pc_entrevistas->es_defensor_ddhh==0)?$marca:'');

                    $template->setValue('es_violencia_genero',($pc_entrevistas->es_violencia_genero==1)?$marca:'');
                    $template->setValue('no_es_violencia',($pc_entrevistas->es_violencia_genero==0)?$marca:'');

                    $template->setValue('es_transtorno_mental',($pc_entrevistas->es_trastorno_mental==1)?$marca:'');
                    $template->setValue('no_es_transtorno_mental',($pc_entrevistas->es_trastorno_mental==0)?$marca:'');

                    $template->setValue('es_vih',($pc_entrevistas->es_vih==1)?$marca:'');
                    $template->setValue('no_es_vih',($pc_entrevistas->es_vih==0)?$marca:'');

                    $template->setValue('observaciones_vulnerabilidad',$observaciones_vulnerabilidad->observaciones);

                    //Atención integral
                    $template->setValue('gestion_medica',($pc_entrevistas->gestion_medica==1)?$marca:'');
                    $template->setValue('no_gestion_medica',($pc_entrevistas->gestion_medica==0)?$marca:'');
                    $template->setValue('gestion_medica_necesidad',$pc_entrevistas->gestion_medica_necesidades);
                    $template->setValue('gestion_medica_derivacion',$pc_entrevistas->gestion_medica_derivacion);

                    $template->setValue('asesoria_juridica',($pc_entrevistas->asesoria_juridica==1)?$marca:'');
                    $template->setValue('no_asesoria_juridica',($pc_entrevistas->asesoria_juridica==0)?$marca:'');
                    $template->setValue('asesoria_juridica_necesidades',$pc_entrevistas->asesoria_juridica_necesidades);
                    $template->setValue('asesoria_juridica_derivacion',$pc_entrevistas->asesoria_juridica_derivacion);

                    $template->setValue('atencion_psicologica',($pc_entrevistas->atencion_psicologica==1)?$marca:'');
                    $template->setValue('no_atencion_psicologica',($pc_entrevistas->atencion_psicologica==0)?$marca:'');
                    $template->setValue('atencion_psicologica_necesidades',$pc_entrevistas->atencion_psicologica_necesidades);
                    $template->setValue('atencion_psicologica_derivacion',$pc_entrevistas->atencion_psicologica_derivacion);

                    $template->setValue('gestion_social',($pc_entrevistas->gestion_social==1)?$marca:'');
                    $template->setValue('no_gestion_social',($pc_entrevistas->gestion_social==0)?$marca:'');
                    $template->setValue('gestion_social_necesidades',$pc_entrevistas->gestion_social_necesidades);
                    $template->setValue('gestion_social_derivacion',$pc_entrevistas->gestion_social_derivacion);

                    //Registro Estatal Victimas
                    $template->setValue('es_rev',($pc_entrevistas->registro_rev==1)?$marca:'');
                    $template->setValue('no_es_rev',($pc_entrevistas->registro_rev==0)?$marca:'');

                    $template->setValue('ejerce_custodia_rev',($pc_entrevistas->ejerce_custodia_rev==1)?$marca:'');
                    $template->setValue('no_ejerce_custodia_rev',($pc_entrevistas->ejerce_custodia_rev==0)?$marca:'');

                    $template->setValue('quien_ejerce_custodia_rev',$pc_entrevistas->quien_ejerce_custodia_rev);
                    $template->setValue('observaciones_rev',$pc_entrevistas->observaciones_rev);
                    $template->setValue('como_se_entero_ceeaiv',$pc_entrevistas->como_se_entero_ceeaiv);

                    $phpWord = new \PhpOffice\PhpWord\PhpWord();
                    $section = $phpWord->addSection();

                    $TableCellStyle = array('borderColor' => 'black', 'borderSize' => 9, 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellSpacing' => 50, 'align' => 'center');
                    $TableFontStyleHeader = array('bold' => true, 'align' => 'center', 'name'=>'Verdana', 'size'=>11);
                    $TableFontStyle = array('align' => 'center', 'name'=>'Verdana', 'size'=>11);
                    $nucleo_familiar = $section->addTable();
                    $nucleo_familiar->addRow(900);
                    $nucleo_familiar->addCell(5000, $TableCellStyle)->addText('Nombre', $TableFontStyleHeader);
                    $nucleo_familiar->addCell(5000, $TableCellStyle)->addText('Edad', $TableFontStyleHeader);
                    $nucleo_familiar->addCell(6000, $TableCellStyle)->addText('Parentesco', $TableFontStyleHeader);
                    $nucleo_familiar->addCell(6000, $TableCellStyle)->addText('Dependencia económica', $TableFontStyleHeader);
                    $nucleo_familiar->addCell(6000, $TableCellStyle)->addText('Factores de vulnerabilidad', $TableFontStyleHeader);

                    foreach ($familiares as $familia) {
                        $nucleo_familiar->addRow();
                        $nucleo_familiar->addCell(5000, $TableCellStyle)->addText("{$familia->nombre_completo}", $TableFontStyle);
                        $nucleo_familiar->addCell(5000, $TableCellStyle)->addText("{$familia->edad}", $TableFontStyle);
                        $nucleo_familiar->addCell(6000, $TableCellStyle)->addText("{$familia->nombre_parentesco}", $TableFontStyle);
                        $nucleo_familiar->addCell(6000, $TableCellStyle)->addText("{$familia->dependencia_economica}", $TableFontStyle);
                        $nucleo_familiar->addCell(6000, $TableCellStyle)->addText("{$familia->factores_vulnerabilidad}", $TableFontStyle);
                    }


                    $template->setComplexBlock('nucleo_familiar', $nucleo_familiar);



                    $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');
                    $template->saveAs($tenpFile);

                    $header = [
                        "Content-Type: application/octet-stream",
                    ];
                } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
                    return back($e->getCode());
                }

                return response()->download($tenpFile, 'primer_contacto.docx', $header)->deleteFileAfterSend($shouldDelete = true);
                break;
            case FormatosEnum::CARTA_ADULTA_REGIONES:
                return response()->download(resource_path('formatos/Carta_compromiso_personas_adultas_regiones.docx'));
                break;
            case FormatosEnum::CARTA_ADULTA_CENTRALES:
                return response()->download(resource_path('formatos/Carta_compromiso_personas_adultas_centrales.docx'));
                break;
            case FormatosEnum::CARTA_NNA_CENTRALES:
                return response()->download(resource_path('formatos/Carta_compromiso_NNA_centrales.docx'));
                break;
            case FormatosEnum::CARTA_NNA_REGIONES:
                return response()->download(resource_path('formatos/Carta_compromiso_NNA_regiones.docx'));
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
