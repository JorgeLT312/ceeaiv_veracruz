<?php

namespace App\Http\Controllers;

use App\Enums\EstatusAtencionEnum;
use App\Enums\NumeroPaso;
use App\Enums\TipoVictimaEnum;
use App\Enums\TipoAutoridadesEnum;
use App\Enums\NacionalidadEnum;
use App\Models\Sexo;
use App\Models\AmbitoCompetencia;
use App\Models\TipoVictima;
use App\Models\IdentidadNac;
use App\Models\Pais;
use App\Models\EntidadFederativa;
use App\Models\Municipios;
use App\Models\Delitos;
use App\Models\Parentesco;
use App\Models\DocumentoProbatorio;
use App\Models\DocProbatorioIdentidad;
use App\Models\TipoTelefono;
use App\Models\General;
use App\Models\SituacionMigratoria;
use App\Models\ViolacionDH;
use App\Models\AmbitoDependencia;
use App\Models\EstadoCivil;
use App\Models\EnfoqueTipoDiscapacidad;
use App\Models\EnfoqueGradoDependencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Exports\RenaviExport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\RenaviRequest;
use App\Enums\AreasEnum;
use App\Enums\RolesEnum;
use App\Models\Notificaciones;
use App\Models\NotificacionesUser;
use App\Models\Role;
use App\Models\Area;
use App\Models\User;
use App\Enums\EstatusNotificacionesEnum;
use App\Enums\EtiquetaNotificacionesEnum;
use Carbon\Carbon;
use Auth;

class RenaviController extends Controller
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
            'pc_entrevistas.updated_at as fecha_atencion')
                ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
                ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
                ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
                ->join('pc_entrevistas', 'generales.id', '=', 'pc_entrevistas.generales_id')
                ->whereBetween('generales.updated_at', [$startDate, $endDate])
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
                        ->orWhere('generales.no_renavi', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$request->search%")
                        ->orWhere('tipo_victimas.nombre', 'like', "%$request->search%");
                })
                ->where(function ($query) {
                    $query->where('generales.estatus_atencion',EstatusAtencionEnum::REV)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::REV_SIN_PC)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::EN_ESPERA_RENAVI)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::RENAVI);
                })
                ->where(function ($query) use ($request){
                    if ($request->estatus_atencion) {
                        $query->where('generales.estatus_atencion',$request->estatus_atencion);
                    }
                })
                ->when($request->datos_pendientes != null, function ($query) use ($request) {
                    $query->where('generales.datos_completo_renavi',$request->datos_pendientes);
                })
                ->latest()
                ->paginate()
                ->withQueryString();

        $filters = $request->only(['search']);
        $sexos = Sexo::all();
        $ambitoCompetencias = AmbitoCompetencia::all();
        $tipoVictimas = TipoVictima::all();
        $nacionalidades = IdentidadNac::all();
        $paises = Pais::all();
        $entidadesFederativas = EntidadFederativa::all();
        $municipios = Municipios::all();
        $delitosProv = Delitos::all();
        $parentescos = Parentesco::all();
        $documentosProbatorios = DocumentoProbatorio::orderBy('nombre','asc')->get();
        $documentosProbatoriosIdentidad = DocProbatorioIdentidad::orderBy('nombre','asc')->get();
        $tipoTelefonos = TipoTelefono::all();
        $situacionesMigratorias = SituacionMigratoria::all();
        $violacionesDh = ViolacionDH::all();
        $ambitosDependencias = AmbitoDependencia::all();
        $estadosCivil = EstadoCivil::all();
        $tiposDiscapacidad = EnfoqueTipoDiscapacidad::all();
        $gradosDependencia = EnfoqueGradoDependencia::all();

        return Inertia::render('Renavi', compact(
            'capturas',
            'filters',
            'sexos',
            'ambitoCompetencias',
            'tipoVictimas',
            'delitosProv',
            'nacionalidades',
            'paises',
            'entidadesFederativas',
            'municipios',
            'parentescos',
            'documentosProbatorios',
            'documentosProbatoriosIdentidad',
            'tipoTelefonos',
            'situacionesMigratorias',
            'violacionesDh',
            'ambitosDependencias',
            'estadosCivil',
            'tiposDiscapacidad',
            'gradosDependencia'
        ));
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
        return redirect()->back();
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
            ->leftJoin('registros_estatales', 'registros_estatales.generales_id', '=', 'generales.id')
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
                'datos_solicitantes.parentescos_id as solicitante_parentescos_id',
                'datos_solicitantes.situaciones_migratorias_id as solicitante_situaciones_migratorias_id',
                'datos_solicitantes.documentos_probatorios_id as solicitante_documentos_probatorios_id',
                'datos_solicitantes.identidad_probatorio_documentos_id as solicitante_identidad_probatorio_documentos_id',
                'datos_solicitantes.ambito_dependencias_id as solicitante_ambito_dependencias_id',
                'datos_solicitantes.cargo as solicitante_cargo',
                'datos_solicitantes.dependencia as solicitante_dependencia',
                'datos_solicitantes.nivel_dependencia as solicitante_nivel_dependencia',
                'hechos_victimizantes.territorios_id as hechos_territorios_id',
                'hechos_victimizantes.paises_id as hechos_paises_id',
                'hechos_victimizantes.entidades_federativas_id as hechos_entidades_federativas_id',
                'hechos_victimizantes.municipios_id as hechos_municipios_id',
                'hechos_victimizantes.poblacion as hechos_poblacion',
                'hechos_victimizantes.relato as hechos_relato',
                'hechos_victimizantes.calle as hechos_calle',
                'hechos_victimizantes.num_exterior as hechos_num_exterior',
                'hechos_victimizantes.num_interior as hechos_num_interior',
                'hechos_victimizantes.colonia as hechos_colonia',
                'hechos_victimizantes.codigo_postal as hechos_codigo_postal',
                'hechos_victimizantes.localidad as hechos_localidad',
                'hechos_victimizantes.fecha_hecho_ini as hechos_fecha_hecho_ini',
                'hechos_victimizantes.fecha_hecho_fin as hechos_fecha_hecho_fin',
                'hechos_victimizantes.conoces_lugar_hechos as hechos_conoces_lugar_hechos',
                'hechos_victimizantes.lo_que_conoces_lugar_hechos as hechos_lo_que_conoces_lugar_hechos',
                'hechos_victimizantes.tipo_danios as hechos_tipo_danios',
                'hechos_victimizantes.ambito_competencia as hechos_ambito_competencia',
                'hechos_victimizantes.delitos_id as hechos_delitos_id',
                'victimas_directas.nombres as victimas_nombres',
                'victimas_directas.primer_apellido as victimas_primer_apellido',
                'victimas_directas.segundo_apellido as victimas_segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                //'hechos_victimizantes.delitos_id',
                'hechos_victimizantes.violaciones_id',
                'victimas_directas.no_carpeta',
                'victimas_directas.fiscalia',
                'victimas_directas.curp',
                'victimas_directas.sexos_id',
                'victimas_directas.territorios_id',
                'victimas_directas.paises_id',
                'victimas_directas.entidades_federativas_id',
                'victimas_directas.municipios_id',
                'victimas_directas.poblacion',
                'victimas_directas.estado_civil_id as victimas_estado_civil',
                'victimas_directas.presenta_identificacion',
                'victimas_directas.identidad_probatorio_documentos_id',
                'victimas_directas.tipo_identificacion_id',
                'victimas_indirectas.nombres as victimas_indirectas_nombres',
                'victimas_indirectas.primer_apellido as victimas_indirectas_primer_apellido',
                'victimas_indirectas.segundo_apellido as victimas_indirectas_segundo_apellido',
                'victimas_indirectas.fecha_nacimiento as victimas_indirectas_fecha_nacimiento',
                'victimas_indirectas.curp as victimas_indirectas_curp',
                'victimas_indirectas.sexos_id as victimas_indirectas_sexos_id',
                'victimas_indirectas.estado_civil_id as victimas_indirectas_estado_civil_id',
                'victimas_indirectas.territorios_id as victimas_indirectas_territorios_id',
                'victimas_indirectas.paises_id as victimas_indirectas_paises_id',
                'victimas_indirectas.entidades_federativas_id as victimas_indirectas_entidades_federativas_id',
                'victimas_indirectas.municipios_id as victimas_indirectas_municipios_id',
                'victimas_indirectas.poblacion as victimas_indirectas_poblacion',
                'victimas_indirectas.parentescos_id as victimas_indirectas_parentescos_id',
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
                'domicilio_notificaciones.telefono',
                'domicilio_notificaciones.tipos_telefonos_id',
                'domicilio_notificaciones.lada',
                'domicilio_notificaciones.extension',
                'factores_vulnerabilidades.*',
                'registros_estatales.lugar_atencion',
                'registros_estatales.fecha_atencion',
                'registros_estatales.folio_expediente',
                'registros_estatales.factores_vulnerabilidades_id',
                'registros_estatales.victimas_indirectas_id',
                'registros_estatales.domicilio_notificaciones_id',
                'registros_estatales.tipo_solicitantes_id',
                'registros_estatales.datos_solicitantes_id',
                'registros_estatales.autoridades_id',
                'registros_estatales.fud_completo',
                )->get()->first();

        $autoridades = DB::table('autoridades')
            ->where('generales_id',$generales_id)
            ->get();

        return response()->json(['data'=>$pc_entrevistas, 'autoridades' => $autoridades]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenaviRequest $request, string $generales_id)
    {
        DB::table('generales')->where('id', $generales_id)
            ->update([
                'datos_completo_renavi' => $request->datos_completo_renavi,
                'created_by' => Auth::id()
            ]);

        switch ($request->numeroPaso) {
            case NumeroPaso::PASO1:
                $this->editarPaso1($request,$generales_id);
                break;
            case NumeroPaso::PASO2:
                $this->editarPaso2($request,$generales_id);
                break;
            case NumeroPaso::PASO3:
                $this->editarPaso3($request,$generales_id);
                break;
            case NumeroPaso::PASO4:
                $this->editarPaso4($request,$generales_id);
                break;
            case NumeroPaso::PASO5:
                $this->editarPaso5($request,$generales_id);
                break;
            case NumeroPaso::PASO6:
                $this->editarPaso6($request,$generales_id);
                break;
            default:
                $this->editarPaso1($request,$generales_id);
                $this->editarPaso2($request,$generales_id);
                $this->editarPaso3($request,$generales_id);
                $this->editarPaso4($request,$generales_id);
                $this->editarPaso5($request,$generales_id);
                $this->editarPaso6($request,$generales_id);
                break;
        }
    }

    public function editarPaso1($request,$generales_id)
    {
        DB::table('generales')->where('id', $generales_id)
            ->update([
                'tipo_victimas_id' => ($request->tipo_victimas_id)?$request->tipo_victimas_id:0,
                'no_rev' => $request->no_rev,
                'created_by' => Auth::id()
            ]);

        DB::table('registros_estatales')->where('generales_id', $generales_id)
            ->update([
                'fecha_atencion' => $request->fecha_atencion,
            ]);

        if ($request->tipo_victimas_id==TipoVictimaEnum::DIRECTA) {
            $esVDirecta = DB::table('victimas_directas')->where('generales_id', $generales_id)->first();
            if($esVDirecta){
                DB::table('victimas_directas')->where('generales_id', $generales_id)->update([
                    'nombres' => ($request->victima_nombres)?$request->victima_nombres:'',
                    'primer_apellido' => ($request->victima_primer_apellido)?$request->victima_primer_apellido:'',
                    'segundo_apellido' => ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'',
                    'fecha_nacimiento' => $request->victima_fecha_nacimiento,
                    'curp' => $request->victima_curp,
                    'sexos_id' => $request->victima_sexos_id,
                ]);
            }
            else{
                $victimaDirecta = new VictimaDirecta;
                $victimaDirecta->nombres = ($request->victima_nombres)?$request->victima_nombres:'';
                $victimaDirecta->primer_apellido = ($request->victima_primer_apellido)?$request->victima_primer_apellido:'';
                $victimaDirecta->segundo_apellido = ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'';
                $victimaDirecta->fecha_nacimiento = $request->victima_fecha_nacimiento;
                $victimaDirecta->curp = $request->victima_curp;
                $victimaDirecta->sexos_id = $request->victima_sexos_id;
                $victimaDirecta->generales_id = $request->generales_id;
                $victimaDirecta->save();
            }
        }
        else{
            $esVIndirecta = DB::table('victimas_indirectas')->where('generales_id', $generales_id)->first();
            if ($esVIndirecta) {
                DB::table('victimas_indirectas')->where('generales_id', $generales_id)->update([
                    'nombres' => ($request->victima_nombres)?$request->victima_nombres:'',
                    'primer_apellido' => ($request->victima_primer_apellido)?$request->victima_primer_apellido:'',
                    'segundo_apellido' => ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'',
                    //Datos nuevos despues de rev
                    'fecha_nacimiento' => $request->victima_fecha_nacimiento,
                    'curp' => $request->victima_curp,
                    'sexos_id' => $request->victima_sexos_id,
                ]);
            }
            else{
                $victimaIndirecta = new VictimaIndirecta;
                $victimaIndirecta->nombres = ($request->victima_nombres)?$request->victima_nombres:'';
                $victimaIndirecta->primer_apellido = ($request->victima_primer_apellido)?$request->victima_primer_apellido:'';
                $victimaIndirecta->segundo_apellido = ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'';
                $victimaIndirecta->generales_id = $generales_id;
                //Datos nuevos despues de rev
                $victimaIndirecta->fecha_nacimiento = $request->victima_fecha_nacimiento;
                $victimaIndirecta->curp = $request->victima_curp;
                $victimaIndirecta->sexos_id = $request->victima_sexos_id;
                $victimaIndirecta->save();
            }
        }

        foreach ($request->autoridades_conocen_hechos as $autoridad) {
            switch ($autoridad) {
                case TipoAutoridadesEnum::MINISTERIAL:
                    DB::table('autoridades')
                        ->where('generales_id', $generales_id)
                        ->where('nombre_autoridad', TipoAutoridadesEnum::MINISTERIAL)
                        ->update([
                            'nombres' => $request->ministerio_publico_nombres,
                            'primer_apellido' => $request->ministerio_publico_primer_apellido,
                            'segundo_apellido' => $request->ministerio_publico_segundo_apellido,
                            'institucion' => $request->ministerio_publico_institucion,
                        ]);
                   break;
                case TipoAutoridadesEnum::PODER_JUDICIAL:
                    DB::table('autoridades')
                        ->where('generales_id', $generales_id)
                        ->where('nombre_autoridad', TipoAutoridadesEnum::PODER_JUDICIAL)
                        ->update([
                            'nombres' => $request->proceso_judicial_nombres,
                            'primer_apellido' => $request->proceso_judicial_primer_apellido,
                            'segundo_apellido' => $request->proceso_judicial_segundo_apellido,
                            'institucion' => $request->proceso_judicial_institucion,
                        ]);
                   break;
                case TipoAutoridadesEnum::DDHH:
                    DB::table('autoridades')
                        ->where('generales_id', $generales_id)
                        ->where('nombre_autoridad', TipoAutoridadesEnum::DDHH)
                        ->update([
                            'nombres' => $request->ddhh_nombres,
                            'primer_apellido' => $request->ddhh_primer_apellido,
                            'segundo_apellido' => $request->ddhh_segundo_apellido,
                            'institucion' => $request->ddhh_institucion,
                        ]);
                   break;
                case TipoAutoridadesEnum::OTRA_AUTORIDAD:
                    DB::table('autoridades')
                        ->where('generales_id', $generales_id)
                        ->where('nombre_autoridad', TipoAutoridadesEnum::OTRA_AUTORIDAD)
                        ->update([
                            'nombres' => $request->otra_nombres,
                            'primer_apellido' => $request->otra_primer_apellido,
                            'segundo_apellido' => $request->otra_segundo_apellido,
                            'institucion' => $request->otra_institucion,
                        ]);
                   break;
           }
        }

        return true;
    }

    public function editarPaso2($request, $generales_id)
    {
        $hechosVictimizantes = DB::table('hechos_victimizantes')->where('generales_id',$generales_id);
        $hechosVictimizantes->update([
            'ambito_competencia' => $request->hechos_ambito_competencia,
            'fecha_hecho_ini' => $request->hechos_inicio,
            'fecha_hecho_fin' => $request->hechos_fin,
            'delitos_id' => json_encode($request->hechos_delitos_id),
        ]);

        $id_victimizante_hechos = DB::table('hechos_victimizantes')->where('generales_id',$generales_id)->first();
        DB::table('tbl_victimizante_hechos_mm_delitos')->where('victimizante_hechos_id', $id_victimizante_hechos->victimizante_hechos_id)->delete();
        for ($ihechosdelitos=0; $ihechosdelitos < count($request->hechos_delitos_id); $ihechosdelitos++) { 
            DB::table('tbl_victimizante_hechos_mm_delitos')->insert([
                'victimizante_hechos_id' => $id_victimizante_hechos->victimizante_hechos_id,
                'delitos_id' => $request->hechos_delitos_id[$ihechosdelitos]
            ]);
        }

        switch ($request->hechos_territorios_id) {
            case NacionalidadEnum::MEXICANA:
                $hechosVictimizantes
                ->update([
                    'territorios_id' => $request->hechos_territorios_id,
                    'entidades_federativas_id' => $request->hechos_entidades_federativas_id,
                    'municipios_id' => $request->hechos_municipios_id,
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

    public function editarPaso3($request, $generales_id)
    {
        if ($request->tipo_solicitantes_id != 1) {
            DB::table('datos_solicitantes')->where('generales_id', $generales_id)
                ->update([
                    'nombres' => ($request->solicitante_nombres)? $request->solicitante_nombres:'',
                    'primer_apellido' => ($request->solicitante_primer_apellido)? $request->solicitante_primer_apellido:'',
                    'segundo_apellido' => ($request->solicitante_segundo_apellido)? $request->solicitante_segundo_apellido:'',
                    'parentescos_id' => ($request->parentesco_id)? $request->parentesco_id:null,
                    'dependencia' => ($request->dependencia)? $request->dependencia:'',
                    'cargo' => ($request->cargo)? $request->cargo:'',
                    'territorios_id' => ($request->territorios_id)? $request->territorios_id:null,
                    'situaciones_migratorias_id' => ($request->situacion_migratoria_id)? $request->situacion_migratoria_id:null,
                    'documentos_probatorios_id' => ($request->identificacion_id)? $request->identificacion_id:null,
                    'identidad_probatorio_documentos_id' => ($request->identidad_probatorio_documentos_id)? $request->identidad_probatorio_documentos_id:null,
                    'entidades_federativas_id' => ($request->entidades_federativas_id)? $request->entidades_federativas_id:null,
                    'ambito_dependencias_id' => ($request->ambito_dependencias_id)? $request->ambito_dependencias_id:null,
                    'nivel_dependencia' => ($request->nivel_dependencias_id)? $request->nivel_dependencias_id:null,
                ]);
            } else {
                if ($request->tipo_victimas_id==TipoVictimaEnum::DIRECTA) {
                    $esVDirecta = DB::table('victimas_directas')->where('generales_id', $generales_id)->first();
                    if($esVDirecta){
                        DB::table('victimas_directas')->where('generales_id', $generales_id)->update([
                            'nombres' => ($request->victima_nombres)?$request->victima_nombres:'',
                            'primer_apellido' => ($request->victima_primer_apellido)?$request->victima_primer_apellido:'',
                            'segundo_apellido' => ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'',
                            'fecha_nacimiento' => $request->victima_fecha_nacimiento,
                            'curp' => $request->victima_curp,
                            'sexos_id' => $request->victima_sexos_id,
                            'estado_civil_id' => $request->estado_civil,
                            'territorios_id' => $request->victima_territorios_id,
                            'paises_id' => $request->victima_paises_id,
                            'entidades_federativas_id' => $request->victima_entidades_federativas_id,
                            'municipios_id' => $request->victima_municipios_id,
                            'poblacion' => $request->victima_poblacion,
                        ]);
                    }
                    else{
                        $victimaDirecta = new VictimaDirecta;
                        $victimaDirecta->nombres = ($request->victima_nombres)?$request->victima_nombres:'';
                        $victimaDirecta->primer_apellido = ($request->victima_primer_apellido)?$request->victima_primer_apellido:'';
                        $victimaDirecta->segundo_apellido = ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'';
                        $victimaDirecta->fecha_nacimiento = $request->victima_fecha_nacimiento;
                        $victimaDirecta->curp = $request->victima_curp;
                        $victimaDirecta->sexos_id = $request->victima_sexos_id;
                        $victimaDirecta->estado_civil_id = $request->estado_civil;
                        $victimaDirecta->territorios_id = $request->victima_territorios_id;
                        $victimaDirecta->paises_id = $request->victima_paises_id;
                        $victimaDirecta->entidades_federativas_id = $request->victima_entidades_federativas_id;
                        $victimaDirecta->municipios_id = $request->victima_municipios_id;
                        $victimaDirecta->poblacion = $request->victima_poblacion;
                        $victimaDirecta->generales_id = $request->generales_id;
                        $victimaDirecta->save();
                    }
                }
                else{
                    $esVIndirecta = DB::table('victimas_indirectas')->where('generales_id', $generales_id)->first();
                    if ($esVIndirecta) {
                        DB::table('victimas_indirectas')->where('generales_id', $generales_id)->update([
                            'nombres' => ($request->victima_nombres)?$request->victima_nombres:'',
                            'primer_apellido' => ($request->victima_primer_apellido)?$request->victima_primer_apellido:'',
                            'segundo_apellido' => ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'',
                            //Datos nuevos despues de rev
                            'fecha_nacimiento' => $request->victima_fecha_nacimiento,
                            'curp' => $request->victima_curp,
                            'sexos_id' => $request->victima_sexos_id,
                            'estado_civil_id' => $request->estado_civil,
                            'territorios_id' => $request->victima_territorios_id,
                            'paises_id' => $request->victima_paises_id,
                            'entidades_federativas_id' => $request->victima_entidades_federativas_id,
                            'municipios_id' => $request->victima_municipios_id,
                            'poblacion' => $request->victima_poblacion,
                            'parentescos_id' => $request->victima_parentesco_id,
                        ]);
                    }
                    else{
                        $victimaIndirecta = new VictimaIndirecta;
                        $victimaIndirecta->nombres = ($request->victima_nombres)?$request->victima_nombres:'';
                        $victimaIndirecta->primer_apellido = ($request->victima_primer_apellido)?$request->victima_primer_apellido:'';
                        $victimaIndirecta->segundo_apellido = ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'';
                        $victimaIndirecta->generales_id = $generales_id;
                        //Datos nuevos despues de rev
                        $victimaIndirecta->fecha_nacimiento = $request->victima_fecha_nacimiento;
                        $victimaIndirecta->curp = $request->victima_curp;
                        $victimaIndirecta->sexos_id = $request->victima_sexos_id;
                        $victimaIndirecta->estado_civil_id = $request->estado_civil;
                        $victimaIndirecta->territorios_id = $request->victima_territorios_id;
                        $victimaIndirecta->paises_id = $request->victima_paises_id;
                        $victimaIndirecta->entidades_federativas_id = $request->victima_entidades_federativas_id;
                        $victimaIndirecta->municipios_id = $request->victima_municipios_id;
                        $victimaIndirecta->poblacion = $request->victima_poblacion;
                        $victimaIndirecta->parentescos_id = $request->victima_parentesco_id;
                        $victimaIndirecta->save();
                    }
                }
            }

        return true;
    }

    public function editarPaso4($request,$generales_id)
    {
        DB::table('victimas_directas')
            ->where('generales_id', $generales_id)
            ->update([
                'identidad_probatorio_documentos_id' => json_encode($request->victima_directa_identidad_probatorio_documentos_id),
                'tipo_identificacion_id' => $request->identificacion_victima_id,
            ]);

        return true;
    }

    public function editarPaso5($request, $generales_id)
    {
        DB::table('domicilio_notificaciones')
            ->where('generales_id', $generales_id)
            ->update([
                'calle' => $request->calle,
                'no_exterior' => $request->no_exterior,
                'no_interior' => $request->no_interior,
                'colonia' => $request->colonia,
                'codigo_postal' => $request->codigo_postal,
                'localidad' => $request->localidad,
                'telefono' => $request->telefono,
                'tipos_telefonos_id' => $request->tipos_telefonos_id,
                'lada' => $request->lada,
                'extension' => $request->extension,
                'territorios_id' => $request->solicitante_territorios_id,
                'paises_id' => $request->solicitante_paises_id,
                'entidades_federativas_id' => $request->solicitante_entidades_federativas_id,
                'municipios_id' => $request->solicitante_municipios_id

            ]);

        return true;
    }

    public function editarPaso6($request, $generales_id)
    {
        DB::table('factores_vulnerabilidades')
            ->where('generales_id', $generales_id)
            ->update([
                'es_adolescente' => $request->es_adolescente,
                'nombre_tutor' => $request->tutor_nombres,
                'primer_apellido_tutor' => $request->tutor_primer_apellido,
                'segundo_apellido_tutor' => $request->tutor_segundo_apellido,
                'datos_contacto_tutor' => $request->tutor_contacto,
                'es_adulto_mayor' => $request->es_adulto_mayor,
                'es_situacion_calle' => $request->es_situacion_calle,
                'es_discapacitado' => $request->es_discapacitado,
                'tipo_discapacidad' => $request->enfoques_tipos_discapacidades_id,
                'grado_dependencia' => $request->enfoque_grado_dependencia_id,
                'es_migrante' => $request->es_migrante,
                'pais_origen' => $request->pais_origen_id,
                'pais_destino' => $request->pais_destino_id,
                'habla_espaniol' => $request->habla_espaniol,
                'requiere_traductor' => $request->requiere_traductor,
                'pertenece_poblacion_indigena' => $request->es_indigena,
                'cual_poblacion_indigena' => $request->poblacion_indigena,
                'es_refugiado' => $request->es_refugiado,
                'es_asilado_politico' => $request->es_asilado,
                'ha_iniciado_tramite_condicion' => $request->tramite_condicion,
                'es_defensor_ddhh' => $request->es_defensor_ddhh,
                'pertenece_instutitucion' => $request->pertenece_institucion,
                'tipo_institucion' => $request->enfoque_tipo_inst_id,
                'otra_institucion' => $request->pertenece_institucion_otra,
                'es_periodista' => $request->es_periodista,
                'tipo_medio_informativo' => $request->tipo_medio_informativo,
                'nombre_medio_informativo' => $request->nombre_medio_informativo,
                'es_desplazado_por_hecho' => $request->es_desplazado_por_hecho,
                'entidad_salida' => $request->entidad_salida_id,
                'entidad_receptora' => $request->entidad_receptora_id,
                'hecho_victimizante_se_debio' => json_encode($request->es_victimizante_por),
                'informacion_violencia_contra_mujeres' => json_encode($request->violencia_contra_mujeres),

            ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function registerInvoice(Request $request, string $generales_id)
    {
        $request->validate([
            'no_renavi' => 'required|unique:generales' 
        ]);

        $general = General::find($generales_id);
        $general->no_renavi = $request->no_renavi;
        $general->estatus_atencion = EstatusAtencionEnum::RENAVI;
        $general->save();

        $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::asigno_status_renavi,null);

        return to_route('renavi.index');
    }

    public function crearNotificacion($generales_id,$etiquetaNotificacion,$asignado_id)
    {
        switch ($etiquetaNotificacion) {
            case EtiquetaNotificacionesEnum::asigno_status_renavi:
                $role_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::REV)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::asigno_status_renavi)->first();
                //crea notificaciones para rol: superior y area: rev
                $usuarios=User::where('role_id',$role_id)->where('area_id',$area_id)->get();
                foreach ($usuarios as $usuario) { 
                    $notificaciones = new NotificacionesUser;
                    $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                    $notificaciones->users_id = $usuario->id;
                    $notificaciones->generales_id = $generales_id;
                    $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                    $notificaciones->save();
                }
            break;
        }
    }

    public function excelRenavi(Request $request)
    {
        return Excel::download(new RenaviExport($request), 'RenaviExport.xlsx');
    }
}
