<?php

namespace App\Http\Controllers;

use App\Enums\EstatusAtencionEnum;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PrimerContacto;
use App\Models\CapturaInicial;
use App\Models\General;
use Carbon\Carbon;
use App\Models\VictimaDirecta;
use App\Models\TipoSolicitante;
use App\Models\IdentidadNac;
use App\Models\Municipios;
use App\Models\Pais;
use App\Models\EntidadFederativa;
use App\Models\Parentesco;
use App\Models\SituacionMigratoria;
use App\Models\DocProbatorioIdentidad;
use App\Models\DocumentoProbatorio;
use App\Models\AmbitoDependencia;
use App\Models\TipoVictima;
use App\Models\Sexo;
use App\Models\TipoTelefono;
use App\Models\DaniosSufridos;
use App\Models\EnfoqueTipoDiscapacidad;
use App\Models\EnfoqueGradoDependencia;
use App\Models\EnfoqueTipoIns;
use App\Models\ViolacionDH;
use App\Models\Delitos;
use App\Models\RegistrosEstatales;
use App\Models\Folios;
use App\Models\Solicitante;
use App\Models\HechosVictimizantes;
use App\Models\DomicilioNotificaciones;
use App\Models\FactoresVulnerabilidades;
use App\Models\Autoridades;
use App\Enums\NumeroPaso;
use App\Enums\NumeroSubPaso;
use App\Enums\NacionalidadEnum;
use App\Enums\TipoVictimaEnum;
use App\Enums\FormatosRevEnum;
use App\Enums\TipoAutoridadesEnum;
use App\Models\VictimaIndirecta;
use App\Http\Requests\SubirEscanerRequest;
use App\Http\Requests\RevRequest;
use App\Http\Requests\RevUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\EstadoCivil;
use App\Enums\FormatosEnum;
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
use App\Models\FoliosRev;
use App\Enums\EstatusNotificacionesEnum;
use App\Enums\EtiquetaNotificacionesEnum;
use App\Models\User;
use GravityMedia\Ghostscript\Ghostscript;
use Symfony\Component\Process\Process;
use Auth;
use DateTime;
//use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
//use GrofGraf\LaravelPDFMerger\PDFMerger;
//use Illuminate\Support\Str;


class RevController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $capturas=General::select('generales.*',
            'victimas_directas.nombres',
            'victimas_directas.primer_apellido',
            'victimas_directas.segundo_apellido',
            'victimas_indirectas.nombres as indirecta_nombres',
            'victimas_indirectas.primer_apellido as indirecta_primer_apellido',
            'victimas_indirectas.segundo_apellido as indirecta_segundo_apellido',
            'pc_entrevistas.modalidad_atencion',
            'pc_entrevistas.registro_rev',
            'pc_entrevistas.updated_at',
            'pc_entrevistas.url_escaner_solicitud_ingreso',
            'pc_entrevistas.url_escaner_fud',
            'pc_entrevistas.url_escaner_acta_nacimiento',
            'pc_entrevistas.url_escaner_curp',
            'pc_entrevistas.url_escaner_iden_oficial',
            'pc_entrevistas.url_escaner_comprobante_domicilio',
            'pc_entrevistas.url_escaner_calidad_victima',
            'pc_entrevistas.url_escaner_cedula',
            'pc_entrevistas.url_escaner_noti_electronica',
            'pc_entrevistas.url_escaner_acuerdo',
            'registros_estatales.fecha_atencion  as fecha_atencion',
            'registros_estatales.folio_expediente',
            'registros_estatales.fud_completo')
                ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
                ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
                ->leftjoin('pc_entrevistas', 'generales.id', '=', 'pc_entrevistas.generales_id')
                ->join('registros_estatales', 'generales.id', '=', 'registros_estatales.generales_id')
                ->where( function ($query) use ($request) {
                    if($request->filter==1){
                        if (!empty($request->startDate)) {
                            \Log::info('tiene filtro y si busca');
                            $startDate = Carbon::parse($request->startDate.' 00:00:00')->toDateTimeString('second');
                            $endDate = Carbon::parse($request->endDate.' 23:59:59')->toDateTimeString('second');
                            $query->whereBetween('registros_estatales.fecha_atencion', [$startDate, $endDate]);
                        }
                    }
                    else{
                        $startDate = Carbon::parse($request->startDate.' 00:00:00')->toDateTimeString('second');
                        $endDate = Carbon::parse($request->endDate.' 23:59:59')->toDateTimeString('second');
                        if(empty($request->startDate)){
                            $startDate = Carbon::now()->subMonth(1)->startOfDay()->toDateTimeString('second');
                        }
                        \Log::info('no tiene filtro y si busca');
                        $query->whereBetween('registros_estatales.fecha_atencion', [$startDate, $endDate]);
                    }
                })
                //->whereBetween('registros_estatales.created_at', [$startDate, $endDate])
                /*->when($request->search, function($query, $search) {
                    $query->where('registros_estatales.folio_expediente', 'like', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                    })*/
                ->where(function ($query) use ($request){
                    $query->where('generales.no_rev','like',"%$request->search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                    TRIM(victimas_directas.primer_apellido), " ", 
                                    TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$request->search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                    TRIM(victimas_indirectas.primer_apellido), " ", 
                                    TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$request->search%")
                        ->orwhere('registros_estatales.folio_expediente', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$request->search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$request->search%");
                })
                ->where(function ($query) {
                    $query->where('generales.estatus_atencion',EstatusAtencionEnum::REV)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::ESPERANDO_APROBACION_REV)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::NO_APROBADO)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::ESPERANDO_REV_SIN_PC)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::REV_SIN_PC)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::EN_ESPERA_RENAVI)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::RENAVI);
                });
                $capturas->latest()
                ->paginate()
                ->withQueryString();

        $filters = $request->only(['search']);
        $tiposSolicitantes = TipoSolicitante::all();
        $nacionalidades = IdentidadNac::all();
        $paises = Pais::all();
        $entidadesFederativas = EntidadFederativa::all();
        $municipios = Municipios::all();
        $parentescos = Parentesco::orderBy('nombre','asc')->get();
        $situacionesMigratorias = SituacionMigratoria::all();
        $documentosProbatorios = DocumentoProbatorio::orderBy('nombre','asc')->get();
        $documentosProbatoriosIdentidad = DocProbatorioIdentidad::orderBy('nombre','asc')->get();
        $ambitosDependencias = AmbitoDependencia::orderBy('nombre','asc')->get();
        $tipoVictimas = TipoVictima::all();
        $sexos = Sexo::all();
        $tipoTelefonos = TipoTelefono::all();
        $tiposDaniosSufridos = DaniosSufridos::all();
        $tiposDiscapacidad = EnfoqueTipoDiscapacidad::all();
        $gradosDependencia = EnfoqueGradoDependencia::all();
        $tiposInstitucion = EnfoqueTipoIns::all();
        $violacionesDh = ViolacionDH::all();
        $delitosProv = Delitos::all();
        $tipoAutoridad = TipoAutoridadesEnum::getValues();
        $estadosCivil = EstadoCivil::all();


        return Inertia::render('Rev', compact('capturas',
            'filters',
            'tiposSolicitantes',
            'nacionalidades',
            'paises',
            'entidadesFederativas',
            'municipios',
            'parentescos',
            'situacionesMigratorias',
            'documentosProbatorios',
            'documentosProbatoriosIdentidad',
            'ambitosDependencias',
            'tipoVictimas',
            'sexos',
            'tipoTelefonos',
            'tiposDaniosSufridos',
            'tiposDiscapacidad',
            'gradosDependencia',
            'tiposInstitucion',
            'violacionesDh',
            'delitosProv',
            'tipoAutoridad',
            'estadosCivil'));
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
    public function store(RevRequest $request)
    {
        if($request->generales_id==null){
            $general = new General;
            $general->no_rev = 'Sin registro';
            $general->estatus_atencion = EstatusAtencionEnum::ESPERANDO_REV_SIN_PC;
            $general->tipo_victimas_id = ($request->tipo_victimas_id)?$request->tipo_victimas_id:0;
            $general->created_by = Auth::id();
            $general->save();

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

            $registro_estatal = new RegistrosEstatales;
            $registro_estatal->lugar_atencion = $request->lugar;
            $registro_estatal->fecha_atencion = $request->fecha_atencion;
            $registro_estatal->folio_expediente = 'CEEAIV-REG/'.$folio_cast.'/'.$folios->anio;
            $registro_estatal->tipo_solicitantes_id = ($request->tipo_solicitantes_id)? $request->tipo_solicitantes_id:0;
            $registro_estatal->generales_id = $general->id;
            $registro_estatal->save();

            $hechos_victimizante = new HechosVictimizantes;
            $hechos_victimizante->generales_id = $general->id;
            $hechos_victimizante->save();

            $domicilio_notificaciones = new DomicilioNotificaciones;
            $domicilio_notificaciones->generales_id = $general->id;
            $domicilio_notificaciones->save();

            $factores_vulnerabilidad = new FactoresVulnerabilidades;
            $factores_vulnerabilidad->generales_id = $general->id;
            $factores_vulnerabilidad->save();

            $pcEntrevista = new PrimerContacto;
            $pcEntrevista->generales_id = $general->id;
            $pcEntrevista->save();

            $solicitante = new Solicitante;
            $solicitante->generales_id = $general->id;
            $solicitante->nombres = ($request->solicitante_nombres)? $request->solicitante_nombres:'';
            $solicitante->primer_apellido = ($request->solicitante_primer_apellido)? $request->solicitante_primer_apellido:'';
            $solicitante->segundo_apellido = ($request->solicitante_segundo_apellido)? $request->solicitante_segundo_apellido:'';
            /*$solicitante->parentescos_id = ($request->parentesco_id)? $request->parentesco_id:0;
            $solicitante->dependencia = ($request->dependencia)? $request->dependencia:0;
            $solicitante->cargo = ($request->cargo)? $request->cargo:0;
            $solicitante->territorios_id = ($request->territorios_id)? $request->territorios_id:0;
            $solicitante->situaciones_migratorias_id = ($request->situacion_migratoria_id)? $request->situacion_migratoria_id:0;
            $solicitante->documentos_probatorios_id = ($request->identificacion_id)? $request->identificacion_id:0;
            $solicitante->identidad_probatorio_documentos_id = ($request->identidad_probatorio_documentos_id)? $request->identidad_probatorio_documentos_id:0;
            $solicitante->entidades_federativas_id = ($request->entidades_federativas_id)? $request->entidades_federativas_id:0;
            $solicitante->ambito_dependencias_id = ($request->ambito_dependencias_id)? $request->ambito_dependencias_id:0;
            $solicitante->nivel_dependencia = ($request->nivel_dependencias_id)? $request->nivel_dependencias_id:0;*/
            $solicitante->save();

            if ($request->tipo_victimas_id==TipoVictimaEnum::DIRECTA) {
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
                $victimaDirecta->generales_id = $general->id;
                $victimaDirecta->save();
            }
            else{
                $victimaIndirecta = new VictimaIndirecta;
                $victimaIndirecta->nombres = ($request->victima_nombres)?$request->victima_nombres:'';
                $victimaIndirecta->primer_apellido = ($request->victima_primer_apellido)?$request->victima_primer_apellido:'';
                $victimaIndirecta->segundo_apellido = ($request->victima_segundo_apellido)?$request->victima_segundo_apellido:'';
                $victimaIndirecta->generales_id = $general->id;
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
        $this->crearNotificacion($general->id,EtiquetaNotificacionesEnum::llena_formulario_rev_registro,null);
        Session::put('generalesId', $general->id);
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
                'hechos_victimizantes.conoces_lugar_hechos as hechos_conoces_lugar_hechos',
                'hechos_victimizantes.lo_que_conoces_lugar_hechos as hechos_lo_que_conoces_lugar_hechos',
                'hechos_victimizantes.tipo_danios as hechos_tipo_danios',
                'victimas_directas.nombres as victimas_nombres',
                'victimas_directas.primer_apellido as victimas_primer_apellido',
                'victimas_directas.segundo_apellido as victimas_segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                'hechos_victimizantes.delitos_id',
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
                'registros_estatales.expediente_completo',
                )->get()->first();

        $autoridades = DB::table('autoridades')
            ->where('generales_id',$generales_id)
            ->get();

        return response()->json(['data'=>$pc_entrevistas, 'autoridades' => $autoridades]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RevUpdateRequest $request, string $generales_id)
    {
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
                $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::lleno_entrevista_pc,null);
                break;
        }


        DB::table('registros_estatales')->where('generales_id', $generales_id)
            ->update([
                'fud_completo' => $request->fud_completo
            ]);
    }

    public function crearNotificacion($generales_id,$etiquetaNotificacion,$asignado_id)
    {
        switch ($etiquetaNotificacion) {
            case EtiquetaNotificacionesEnum::llena_formulario_rev:
                $role_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::REV)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::llena_formulario_rev)->first();
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
            case EtiquetaNotificacionesEnum::llena_formulario_rev_registro:
                $role_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::REV)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::llena_formulario_rev_registro)->first();
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
            case EtiquetaNotificacionesEnum::asigno_estatus_rev:
                $role_id=Role::where('name',RolesEnum::OPERATIVO)->pluck('id');
                $area_id=Area::where('nombre',AreasEnum::REV)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::asigno_estatus_rev)->first();
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
            case EtiquetaNotificacionesEnum::asigno_estatus_rev_sin_pc:
                $role_operativo_id=Role::where('name',RolesEnum::OPERATIVO)->pluck('id');
                $area_rev_id=Area::where('nombre',AreasEnum::REV)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::asigno_estatus_rev_sin_pc_operativo)->first();
                //crear notificacion para usuario rol: operativo y area: rev
                $usuarios_operativo_rev=User::where('role_id',$role_operativo_id)->where('area_id',$area_rev_id)->get();
                foreach ($usuarios_operativo_rev as $usuario) { 
                    $notificaciones = new NotificacionesUser;
                    $notificaciones->notificaciones_id = $notificaciones_id->notificaciones_id;
                    $notificaciones->users_id = $usuario->id;
                    $notificaciones->generales_id = $generales_id;
                    $notificaciones->estatus = EstatusNotificacionesEnum::SIN_VER;
                    $notificaciones->save();
                }

                $role_superior_id=Role::where('name',RolesEnum::SUPERIOR)->pluck('id');
                $area_pc_id=Area::where('nombre',AreasEnum::PC)->pluck('id');
                $notificaciones_id=Notificaciones::where('etiqueta',EtiquetaNotificacionesEnum::asigno_estatus_rev_sin_pc_superior)->first();
                //crear notificacion para usuario rol: operativo y area: rev
                $usuarios_superior_pc=User::where('role_id',$role_superior_id)->where('area_id',$area_pc_id)->get();
                foreach ($usuarios_superior_pc as $usuario) { 
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

    public function editarPaso1($request,$generales_id)
    {
        $registro_estatal = DB::table('registros_estatales')->where('generales_id', $generales_id)
            ->update([
                'lugar_atencion' => $request->lugar,
                'fecha_atencion' => $request->fecha_atencion,
                'tipo_solicitantes_id' => $request->tipo_solicitantes_id
            ]);

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

        return true;
    }


    public function editarPaso2($request,$generales_id)
    {
        // subpaso 1
        DB::table('generales')->where('id', $generales_id)
            ->update([
                'tipo_victimas_id' => ($request->tipo_victimas_id)?$request->tipo_victimas_id:0,
                'created_by' => Auth::id()
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

        // subpaso 2
        $domicilioNotificaciones = DB::table('domicilio_notificaciones')->where('generales_id', $generales_id)->first();
        if ($domicilioNotificaciones) {
            DB::table('domicilio_notificaciones')
            ->where('generales_id', $request->generales_id)
            ->update([
                'calle' => $request->calle,
                'no_exterior' => $request->no_exterior,
                'no_interior' => $request->no_interior,
                'colonia' => $request->colonia,
                'codigo_postal' => $request->codigo_postal,
                'localidad' => $request->localidad,
                'telefono' => $request->telefono,
                'tipos_telefonos_id' => $request->tipos_telefonos_id,
                'territorios_id' => $request->solicitante_territorios_id,
                'paises_id' => $request->solicitante_paises_id,
                'entidades_federativas_id' => $request->solicitante_entidades_federativas_id,
                'municipios_id' => $request->solicitante_municipios_id

            ]);
        }
        else{
            $domicilio_notificaciones = new DomicilioNotificaciones;
            $domicilio_notificaciones->generales_id = $request->generales_id;
            $domicilio_notificaciones->calle = $request->calle;
            $domicilio_notificaciones->no_exterior = $request->no_exterior;
            $domicilio_notificaciones->no_interior = $request->no_interior;
            $domicilio_notificaciones->colonia = $request->colonia;
            $domicilio_notificaciones->codigo_postal = $request->codigo_postal;
            $domicilio_notificaciones->localidad = $request->localidad;
            $domicilio_notificaciones->telefono = $request->telefono;
            $domicilio_notificaciones->tipos_telefonos_id = $request->tipos_telefonos_id;
            $domicilio_notificaciones->territorios_id = $request->solicitante_territorios_id;
            $domicilio_notificaciones->paises_id = $request->solicitante_paises_id;
            $domicilio_notificaciones->entidades_federativas_id = $request->solicitante_entidades_federativas_id;
            $domicilio_notificaciones->municipios_id = $request->solicitante_municipios_id;
            $domicilio_notificaciones->save();
        }

        return true;
    }

    public function editarPaso3($request,$generales_id)
    {
        $esVDirecta = DB::table('victimas_directas')->where('generales_id', $generales_id)->first();
        if($esVDirecta){
            logger($request->identificacion_victima_id);
            DB::table('victimas_directas')->where('generales_id', $generales_id)->update([
                'nombres' => ($request->victima_directa_nombres)?$request->victima_directa_nombres:'',
                'primer_apellido' => ($request->victima_directa_primer_apellido)?$request->victima_directa_primer_apellido:'',
                'segundo_apellido' => ($request->victima_directa_segundo_apellido)?$request->victima_directa_segundo_apellido:'',
                'identidad_probatorio_documentos_id' => json_encode($request->victima_directa_identidad_probatorio_documentos_id),
                'parentescos_id' => $request->victima_parentesco_id,
                'presenta_identificacion' => $request->identificacion_victima,
                'tipo_identificacion_id' => $request->identificacion_victima_id,
            ]);
            logger(json_encode(DB::table('victimas_directas')->where('generales_id', $generales_id)->first()));
        }
        else{
            $victimaDirecta = new VictimaDirecta;
            $victimaDirecta->nombres = ($request->victima_directa_nombres)?$request->victima_directa_nombres:'';
            $victimaDirecta->primer_apellido = ($request->victima_directa_primer_apellido)?$request->victima_directa_primer_apellido:'';
            $victimaDirecta->segundo_apellido = ($request->victima_directa_segundo_apellido)?$request->victima_directa_segundo_apellido:'';
            $victimaDirecta->identidad_probatorio_documentos_id = json_encode($request->victima_directa_identidad_probatorio_documentos_id);
            $victimaDirecta->parentescos_id = $request->victima_parentesco_id;
            $victimaDirecta->presenta_identificacion = $request->identificacion_victima;
            $victimaDirecta->tipo_identificacion_id = $request->identificacion_victima_id;
            $victimaDirecta->generales_id = $generales_id;
            $victimaDirecta->save();
        }
        return true;
    }

    public function editarPaso4($request)
    {
        $hechosVictimizantes = DB::table('hechos_victimizantes')->where('generales_id',$request->generales_id);
        $hechosVictimizantes->update([
            'relato' => $request->hechos_relato,
            'conoces_lugar_hechos' => $request->conoce_lugar_hechos,
            'lo_que_conoces_lugar_hechos' => $request->conoce_hechos_relato,
            'calle' => $request->hechos_calle,
            'num_exterior' => $request->hechos_no_exterior,
            'num_interior' => $request->hechos_no_interior,
            'colonia' => $request->hechos_colonia,
            'codigo_postal' => $request->hechos_codigo_postal,
            'localidad' => $request->hechos_localidad,
            'fecha_hecho_ini' => $request->hechos_fecha,
        ]);
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

   /*victima_danios_sufridos_id: []*/

    public function editarPaso5($request,$generales_id)
    {
        DB::table('hechos_victimizantes')
            ->where('generales_id',$request->generales_id)
            ->update([
                'tipo_danios' => json_encode($request->victima_danios_sufridos_id),
            ]);
        Autoridades::where('generales_id', $generales_id)->delete();
        foreach ($request->autoridades_conocen_hechos as $autoridad) {
            switch ($autoridad) {
                case TipoAutoridadesEnum::MINISTERIAL:
                    $autoridades_ministerial = new Autoridades;
                    $autoridades_ministerial->denuncio_ante_ministerio = $request->ministerio_publico_denuncia;
                    $autoridades_ministerial->fecha = $request->ministerio_publico_fecha_denuncia;
                    $autoridades_ministerial->competencia = $request->ministerio_publico_competencia;
                    $autoridades_ministerial->entidades_federativas_id = $request->ministerio_publico_entidades_federativas_id;
                    $autoridades_ministerial->violaciones_id = $request->ministerio_publico_violaciones_id;
                    $autoridades_ministerial->delitos_id = $request->ministerio_publico_delitos_id;
                    $autoridades_ministerial->agencia_mp = $request->ministerio_publico_agencia;
                    $autoridades_ministerial->ap_ci_ac = $request->ministerio_publico_apciac;
                    $autoridades_ministerial->estado_investigacion = $request->ministerio_publico_estado_investigacion;
                    $autoridades_ministerial->observaciones = $request->autoridad_observaciones;
                    $autoridades_ministerial->nombre_autoridad = TipoAutoridadesEnum::MINISTERIAL;
                    $autoridades_ministerial->generales_id = $generales_id;
                    $autoridades_ministerial->save();
                   break;
                case TipoAutoridadesEnum::PODER_JUDICIAL:
                    $autoridades_judicial = new Autoridades;
                    $autoridades_judicial->fecha = $request->proceso_judicial_fecha;
                    $autoridades_judicial->competencia = $request->proceso_judicial_competencia;
                    $autoridades_judicial->entidades_federativas_id = $request->proceso_judicial_entidades_federativas_id;
                    $autoridades_judicial->violaciones_id = $request->proceso_judicial_violaciones_id;
                    $autoridades_judicial->delitos_id = $request->proceso_judicial_delitos_id;
                    $autoridades_judicial->num_juzgado = $request->proceso_judicial_numero_juzgado;
                    $autoridades_judicial->num_proceso = $request->proceso_judicial_numero_proceso;
                    $autoridades_judicial->estado_proceso_judicial = $request->proceso_judicial_estado_proceso;
                    $autoridades_judicial->observaciones = $request->autoridad_observaciones;
                    $autoridades_judicial->nombre_autoridad = TipoAutoridadesEnum::PODER_JUDICIAL;
                    $autoridades_judicial->generales_id = $generales_id;
                    $autoridades_judicial->save();
                   break;
                case TipoAutoridadesEnum::DDHH:
                    $autoridades_ddhh = new Autoridades;
                    $autoridades_ddhh->presento_queja_ddhh = $request->ddhh_queja;
                    $autoridades_ddhh->fecha = $request->ddhh_fecha_queja;
                    $autoridades_ddhh->competencia = $request->ddhh_competencia;
                    $autoridades_ddhh->organismo = $request->ddhh_organismo;
                    $autoridades_ddhh->violaciones_id = $request->ddhh_violaciones_id;
                    $autoridades_ddhh->autoridad_responsable = $request->ddhh_autoridad_responsable;
                    $autoridades_ddhh->tipo_resolucion = $request->ddhh_tipo_resolucion;
                    $autoridades_ddhh->folio = $request->ddhh_folio;
                    $autoridades_ddhh->estado_actual = $request->ddhh_estado_actual;
                    $autoridades_ddhh->observaciones = $request->autoridad_observaciones;
                    $autoridades_ddhh->generales_id = $generales_id;
                    $autoridades_ddhh->nombre_autoridad = TipoAutoridadesEnum::DDHH;
                    $autoridades_ddhh->save();
                   break;
                case TipoAutoridadesEnum::OTRA_AUTORIDAD:
                    $autoridades_otra = new Autoridades;
                    $autoridades_otra->nombre_autoridad = TipoAutoridadesEnum::OTRA_AUTORIDAD;
                    $autoridades_otra->nombre_otra_autoridad = $request->otra_nombre_autoridad;
                    $autoridades_otra->observaciones = $request->autoridad_observaciones;
                    $autoridades_otra->generales_id = $generales_id;
                    $autoridades_otra->save();
                   break;
           }
        }
        return true;
    }

    public function editarPaso6($request)
    {
        logger($request->es_discapacitado);
        DB::table('factores_vulnerabilidades')
            ->where('generales_id', $request->generales_id)
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

    public function subirArchivo(SubirEscanerRequest $request){
        if(!empty($request->file)){
            $filename = $request->tipo_formato.'-'.$request->generales_id.'.pdf';
            Storage::disk('public')->put('escaner/'.$filename, file_get_contents($request->file));
            
            $filecontent = file_get_contents(storage_path() . '/app/public/escaner/'.$filename);
        
            if (preg_match("/^%PDF-1.6/", $filecontent)) {
                $ghostscript = new Ghostscript([
                    'quiet' => false
                ]);

                $inputPdf = storage_path() . '/app/public/escaner/'.$filename;
                $outputPdf = storage_path() . '/app/public/escaner/v4-'.$filename;

                // Ejecuta el comando Ghostscript para cambiar la versión del PDF
                $process = new Process([
                    'gs',//Es posible que este comando no funcione en local
                    '-q', // Modo silencioso
                    '-dNOPAUSE',
                    '-dBATCH',
                    '-dCompatibilityLevel=1.4', // Especifica la versión 1.4
                    '-sDEVICE=pdfwrite',
                    "-sOutputFile={$outputPdf}",
                    $inputPdf
                ]);
                $process->run();

                Storage::delete('public/escaner/'.$filename);
                Storage::move('public/escaner/v4-'.$filename, 'public/escaner/'.$filename);
            } 

            switch ($request->tipo_formato) {
                case FormatosRevEnum::SOLICITUD_INGRESO:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_solicitud_ingreso' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::FUD:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_fud' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::ACTA_NACIMIENTO:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_acta_nacimiento' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::CURP:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_curp' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::IDENTIFICACION_OFICIAL:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_iden_oficial' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::COMPROBANTE_DOMICILIO:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_comprobante_domicilio' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::CONSTANCIA_CALIDAD:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_calidad_victima' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::CEDULA:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_cedula' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::NOTIFICACION_ELECTRONICA:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_noti_electronica' => 'storage/escaner/'.$filename,
                    ]);
                    break;
                case FormatosRevEnum::ACUERDO:
                    DB::table('pc_entrevistas')
                    ->where('generales_id', $request->generales_id)
                    ->update([
                        'url_escaner_acuerdo' => 'storage/escaner/'.$filename,
                    ]);
                    break;
            }
        }
        return to_route('rev.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changeStatus(Request $request, string $generales_id)
    {
        $request->validate([
            'estatus_atencion' => 'required'
        ]);

        if ($request->estatus_atencion==EstatusAtencionEnum::REV) {
            $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::asigno_estatus_rev,null);
        }
        if ($request->estatus_atencion==EstatusAtencionEnum::REV_SIN_PC) {
            $this->crearNotificacion($generales_id,EtiquetaNotificacionesEnum::asigno_estatus_rev_sin_pc,null);
        }

        $general = General::find($generales_id);
        $general->estatus_atencion = $request->estatus_atencion;

        if (($general->no_rev == '' || $general->no_rev == 'Sin registro') && ($request->estatus_atencion == EstatusAtencionEnum::REV || $request->estatus_atencion == EstatusAtencionEnum::REV_SIN_PC)) {
            //Inserta un registro en: Registro estatal
            $ultimo_folio=FoliosRev::get()->last();
            //Verifica si no existe ningun folio o si es un año nuevo se reinicia los folios
            if(empty($ultimo_folio) || date('Y') > $ultimo_folio->anio){
                $folios = new FoliosRev;
                $folios->folio = 1;
                $folios->anio = date('Y');
                $folios->save();
            }
            else{
                $folios = new FoliosRev;
                $folios->folio = $ultimo_folio->folio+1;
                $folios->anio = date('Y');
                $folios->save();
            }
            //Se agregan 0 faltantes
            (strlen($folios->folio) < 5) ? $folio_cast=str_pad($folios->folio, 5, "0", STR_PAD_LEFT) : $folio_cast=$folios->folio;

            $general->no_rev = 'CEEAIV/REV/'.$folio_cast.'/'.$folios->anio;
        }

        $general->save();
    }

    public function imprimirRev($tipo_formato,$generales_id)
    {
        $rev = DB::table('generales')
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
                'hechos_victimizantes.conoces_lugar_hechos as hechos_conoces_lugar_hechos',
                'hechos_victimizantes.lo_que_conoces_lugar_hechos as hechos_lo_que_conoces_lugar_hechos',
                'hechos_victimizantes.tipo_danios as hechos_tipo_danios',
                'victimas_directas.nombres as victimas_nombres',
                'victimas_directas.primer_apellido as victimas_primer_apellido',
                'victimas_directas.segundo_apellido as victimas_segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                'hechos_victimizantes.delitos_id',
                'hechos_victimizantes.violaciones_id',
                'victimas_directas.no_carpeta',
                'victimas_directas.fiscalia',
                'victimas_directas.curp as victimas_curp',
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
                )->first();
                $marca='✓';
                $autoridades = DB::table('autoridades')
                ->select('autoridades.*','delitos.nombre as delito_ministerial')
                ->leftJoin('delitos','delitos.delitos_id','autoridades.delitos_id')
                ->where('generales_id',$generales_id)
                ->where('nombre_autoridad','Investigación ministerial')
                ->first();
                $autoridades_judicial = DB::table('autoridades')
                ->select('entidades_federativas.name as entidad_judicial','autoridades.*','delitos.nombre as delito_judicial')
                ->leftJoin('entidades_federativas','entidades_federativas.entidades_federativas_id','autoridades.entidades_federativas_id')
                ->leftJoin('delitos','delitos.delitos_id','autoridades.delitos_id')
                ->where('generales_id',$generales_id)
                ->where('nombre_autoridad','Proceso judicial')
                ->first();
                $autoridades_ddhh = DB::table('autoridades')
                ->select('entidades_federativas.name as entidad_judicial','autoridades.*','delitos.nombre as delito_ddhh','violaciones.nombre as violacion_ddhh')
                ->leftJoin('entidades_federativas','entidades_federativas.entidades_federativas_id','autoridades.entidades_federativas_id')
                ->leftJoin('delitos','delitos.delitos_id','autoridades.delitos_id')
                ->leftJoin('violaciones','violaciones.violaciones_id','autoridades.violaciones_id')
                ->where('generales_id',$generales_id)
                ->where('nombre_autoridad','Procedimientos ante DDHH')
                ->first();
                $autoridades_otra = DB::table('autoridades')
                ->where('generales_id',$generales_id)
                ->where('nombre_autoridad','Otra autoridad')
                ->first();
                $parentesco_solicitante = DB::table('parentescos')
                ->where('parentescos_id',$rev->solicitante_parentescos_id)
                ->first();
                $tipo_victima= DB::table('generales')
                ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
                ->where('id',$generales_id)
                ->first();
                $sexo=DB::table('sexos')->where('sexos_id',$rev->sexos_id)->first();
                $estados_civil=DB::table('estados_civil')->where('id',$rev->victimas_estado_civil)->first();
                $datos_solicitantes=DB::table('datos_solicitantes')
                ->select('municipios.name as nombre_municipio','territorios.nombre as nombre_territorio',
                    'telefonos','entidades_federativas.name as nombre_entidad')
                ->join('territorios','territorios.territorios_id','datos_solicitantes.territorios_id')
                ->join('municipios','municipios.municipios_id','datos_solicitantes.municipios_id')
                ->join('entidades_federativas','entidades_federativas.entidades_federativas_id','datos_solicitantes.entidades_federativas_id')
                ->where('generales_id',$generales_id)->first();

                $hechos_lugar=DB::table('hechos_victimizantes')
                ->select('municipios.name as nombre_municipio','territorios.nombre as nombre_territorio','entidades_federativas.name as nombre_entidad')
                ->join('territorios','territorios.territorios_id','hechos_victimizantes.territorios_id')
                ->join('municipios','municipios.municipios_id','hechos_victimizantes.municipios_id')
                ->join('entidades_federativas','entidades_federativas.entidades_federativas_id','hechos_victimizantes.entidades_federativas_id')
                ->where('generales_id',$generales_id)->first();
                $texto_danios='';
                $danios1=str_replace ( '[', '', $rev->hechos_tipo_danios);
                $danios2=str_replace ( ']', '', $danios1);
                $danios3=explode(",", $danios2);
                $entidad_ministerial=DB::table('autoridades')
                ->select('entidades_federativas.name as entidad_ministerial')
                ->join('entidades_federativas','entidades_federativas.entidades_federativas_id','autoridades.entidades_federativas_id')
                ->where('generales_id',$generales_id)->first();
                for ($itipodanios=0; $itipodanios < count($danios3); $itipodanios++) { 
                    $danio=DaniosSufridos::where('danios_sufridos_id',$danios3[$itipodanios])->first();
                    $texto_danios=$texto_danios.' / '.$danio->nombre;
                }
                $nombres=($rev->victimas_nombres)?$rev->victimas_nombres:$rev->victimas_indirectas_nombres;
                $primer_apellido=($rev->victimas_primer_apellido)?$rev->victimas_nombres:$rev->victimas_indirectas_primer_apellido;
                $segundo_apellido=($rev->victimas_segundo_apellido)?$rev->victimas_nombres:$rev->victimas_indirectas_segundo_apellido;
                $nombre_completo_victima=$nombres.' '.$primer_apellido.' '.$segundo_apellido;
                $role_id=Role::where('name',RolesEnum::SUPERIOR)->first();
                $area_id=Area::where('nombre',AreasEnum::REV)->first();
                $nombre_superior_rev=User::where('role_id',$role_id->id)->where('area_id',$area_id->id)->first();
                
                $mesesEnEspanol = [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre',
                    ];
             
                $fecha = Carbon::createFromFormat('Y-m-d', $rev->fecha_atencion);

                
                $fecha_atencion = $fecha->day.' de '.$mesesEnEspanol[$fecha->month].' de '.$fecha->year;


        switch ($tipo_formato) {
            case FormatosEnum::FUD:
                $template_fud = new TemplateProcessor(resource_path('FUD.docx'));
                //Datos word
                $template_fud->setValue('tipo_victima',($tipo_victima)?$tipo_victima->nombre:'');
                $template_fud->setValue('sexo',($sexo)?$sexo->nombre:'');
                $template_fud->setValue('estados_civil',($estados_civil)?$estados_civil->nombre:'');
                $template_fud->setValue('denuncio_mp',($estados_civil)?$estados_civil->nombre:'');
                $template_fud->setValue('no_denuncio_mp',($estados_civil)?$estados_civil->nombre:'');
                $template_fud->setValue('fecha_denuncia_mp',($estados_civil)?$estados_civil->nombre:'');
                $template_fud->setValue('lugar_solicitud',$rev->lugar_atencion);
                $template_fud->setValue('fecha_solicitud',$rev->fecha_atencion);
                $template_fud->setValue('nombres_solicitante',$rev->solicitantes_nombres);
                $template_fud->setValue('primer_apellido_solicitante',$rev->solicitantes_primer_apellido);
                $template_fud->setValue('segundo_apellido_solicitante',$rev->solicitantes_segundo_apellido);
                $template_fud->setValue('parentesco_solicitante',($parentesco_solicitante)?$parentesco_solicitante->nombre:'');
                $template_fud->setValue('cargo_solicitante',$rev->solicitante_cargo);
                $template_fud->setValue('dependencia_solicitante',$rev->solicitante_dependencia);
                $template_fud->setValue('nombres_victima',$rev->victimas_nombres);
                $template_fud->setValue('primer_apellido_victima',$rev->victimas_primer_apellido);
                $template_fud->setValue('segundo_apellido_victima',$rev->victimas_segundo_apellido);
                $template_fud->setValue('fecha_nacimiento_victima',$rev->victimas_fecha_nacimiento);
                $template_fud->setValue('curp_victima',$rev->victimas_curp);
                $template_fud->setValue('calle_solicitante',$rev->calle);
                $template_fud->setValue('ex_solicitante',$rev->no_exterior);
                $template_fud->setValue('int_solicitante',$rev->no_interior);
                $template_fud->setValue('cp_solicitante',$rev->codigo_postal);
                $template_fud->setValue('colonia_solicitante',$rev->colonia);
                $template_fud->setValue('localidad_solicitante',$rev->localidad);
                $template_fud->setValue('nacionalidad_victima',($datos_solicitantes)?$datos_solicitantes->nombre_territorio:'');
                $template_fud->setValue('municipio_solicitante',($datos_solicitantes)?$datos_solicitantes->nombre_municipio:'');
                $template_fud->setValue('entidad_solicitante',($datos_solicitantes)?$datos_solicitantes->nombre_entidad:'');
                $template_fud->setValue('telefono_solicitante',$rev->telefono);
                $template_fud->setValue('ex_hechos',$rev->hechos_num_exterior);
                $template_fud->setValue('int_hechos',$rev->hechos_num_interior);
                $template_fud->setValue('cp_hechos',$rev->hechos_codigo_postal);
                $template_fud->setValue('colonia_hechos',$rev->hechos_colonia);
                $template_fud->setValue('calle_hechos',$rev->hechos_calle);
                $template_fud->setValue('localidad_hechos',$rev->hechos_localidad);
                $template_fud->setValue('fecha_hechos',$rev->hechos_fecha_hecho_ini);
                $template_fud->setValue('relato_hechos',$rev->hechos_relato);
                $template_fud->setValue('agencia_mp',($autoridades)?$autoridades->agencia_mp:'');
                $template_fud->setValue('apc_iac',($autoridades)?$autoridades->ap_ci_ac:'');
                $template_fud->setValue('estado_investigacion',($autoridades)?$autoridades->estado_investigacion:'');
                $template_fud->setValue('autoridad_responsable',($autoridades)?$autoridades->autoridad_responsable:'');
                $template_fud->setValue('folio',$rev->folio_expediente);
                $template_fud->setValue('estado_actual',($autoridades)?$autoridades->estado_actual:'');
                $template_fud->setValue('otra_autoridad',($autoridades)?$autoridades->nombre_otra_autoridad:'');
                $template_fud->setValue('queja',($autoridades)?$autoridades->presento_queja_ddhh:'');
                $template_fud->setValue('organismo',($autoridades)?$autoridades->organismo:'');
                $template_fud->setValue('adolecente',($rev->es_adolescente==1)?'SI':'NO');
                $template_fud->setValue('nombre_tutor',$rev->nombre_tutor.' '.$rev->primer_apellido_tutor.' '.$rev->segundo_apellido_tutor);
                $template_fud->setValue('datos_contacto_tutor',$rev->datos_contacto_tutor);
                $template_fud->setValue('adulto_mayor',($rev->es_adulto_mayor==1)?'SI':'NO');
                $template_fud->setValue('discapacidad',($rev->es_discapacitado==1)?'SI':'NO');
                $template_fud->setValue('migrante',($rev->es_migrante==1)?'SI':'NO');
                $template_fud->setValue('defensor',($rev->es_defensor_ddhh==1)?'SI':'NO');
                $template_fud->setValue('periodista',($rev->es_periodista==1)?'SI':'NO');
                $template_fud->setValue('refugiado',($rev->es_refugiado==1)?'SI':'NO');
                $template_fud->setValue('espaniol',($rev->habla_espaniol==1)?'SI':'NO');
                $template_fud->setValue('traductor',($rev->requiere_traductor==1)?'SI':'NO');
                $template_fud->setValue('politico',($rev->es_asilado_politico==1)?'SI':'NO');
                $template_fud->setValue('poblacion_indigena',($rev->pertenece_poblacion_indigena==1)?'SI':'NO');
                $template_fud->setValue('desplazado',($rev->es_desplazado_por_hecho==1)?'SI':'NO');
                $template_fud->setValue('institucion',($rev->pertenece_instutitucion==1)?'SI':'NO');
                $template_fud->setValue('situacion_calle',($rev->es_situacion_calle==1)?'SI':'NO');
                $template_fud->setValue('pais_origen',$rev->pais_origen);
                $template_fud->setValue('pais_destino',$rev->pais_destino);
                $template_fud->setValue('cual_poblacion',$rev->cual_poblacion_indigena);
                $template_fud->setValue('tramite',$rev->ha_iniciado_tramite_condicion);
                $template_fud->setValue('lugar_hechos','');
                $template_fud->setValue('correo_solicitante','');
                $template_fud->setValue('pais_victima','');
                $template_fud->setValue('entidad_victima','');
                $template_fud->setValue('municipio_victima','');
                $template_fud->setValue('poblacion_victima','');
                $template_fud->setValue('municipio_hechos',($hechos_lugar)?$hechos_lugar->nombre_municipio:'');
                $template_fud->setValue('entidad_hechos',($hechos_lugar)?$hechos_lugar->nombre_entidad:'');
                $template_fud->setValue('atoridad_hechos','');
                $template_fud->setValue('fecha_inicio_judicial',($autoridades_judicial)?$autoridades_judicial->fecha:'');
                $template_fud->setValue('entidad_autoridad','');
                $template_fud->setValue('proceso_delito','');
                $template_fud->setValue('proceso_estado','');
                $template_fud->setValue('violacion_dd_hh','');
                $template_fud->setValue('autoridad_hechos','');
                $template_fud->setValue('estado_investigacion',($autoridades)?$autoridades->estado_investigacion:'');
                $template_fud->setValue('tipos_danios',$texto_danios);
                $template_fud->setValue('lengua','');
                $template_fud->setValue('denuncio_ante_ministerio',($autoridades)?($autoridades->denuncio_ante_ministerio==1)?'Si':'No':'');
                $template_fud->setValue('fecha_ministerio',($autoridades)?$autoridades->fecha:'');
                $template_fud->setValue('competencia',($autoridades)?$autoridades->competencia:'');
                $template_fud->setValue('entidad_ministerial',($entidad_ministerial)?$entidad_ministerial->entidad_ministerial:'');
                $template_fud->setValue('competencia_judicial',($autoridades_judicial)?$autoridades_judicial->competencia:'');
                $template_fud->setValue('entidad_judicial',($autoridades_judicial)?$autoridades_judicial->entidad_judicial:'');
                $template_fud->setValue('num_proceso',($autoridades_judicial)?$autoridades_judicial->num_proceso:'');
                $template_fud->setValue('num_juzgado',($autoridades_judicial)?$autoridades_judicial->num_juzgado:'');
                $template_fud->setValue('delito_judicial',($autoridades_judicial)?$autoridades_judicial->delito_judicial:'');
                $template_fud->setValue('estado_invetigacion_judicial',($autoridades_judicial)?$autoridades_judicial->estado_proceso_judicial:'');
                $template_fud->setValue('delito_ministerial',($autoridades)?$autoridades->delito_ministerial:'');
                $template_fud->setValue('fecha_ddhh',($autoridades_ddhh)?$autoridades_ddhh->fecha:'');
                $template_fud->setValue('competencia_ddhh',($autoridades_ddhh)?$autoridades_ddhh->competencia:'');
                $template_fud->setValue('entidad_ddhh',($autoridades_ddhh)?$autoridades_ddhh->entidad_judicial:'');
                $template_fud->setValue('num_proceso_judicial',($autoridades_ddhh)?$autoridades_ddhh->num_proceso:'');
                $template_fud->setValue('num_juzgado_judicial',($autoridades_ddhh)?$autoridades_ddhh->num_juzgado:'');
                $template_fud->setValue('delito_ddhh',($autoridades_ddhh)?$autoridades_ddhh->delito_ddhh:'');
                $template_fud->setValue('estado_invetigacion_ddhh',($autoridades_ddhh)?$autoridades_ddhh->estado_proceso_judicial:'');
                $template_fud->setValue('autoridad_responsable_ddhh',($autoridades_ddhh)?$autoridades_ddhh->nombre_autoridad:'');
                $template_fud->setValue('presento_queja_ddhh',($autoridades_ddhh)?($autoridades_ddhh->presento_queja_ddhh=='1')?'Si':'No':'');
                $template_fud->setValue('folio_ddhh',($autoridades_ddhh)?$autoridades_ddhh->folio:'');
                $template_fud->setValue('estado_actual_ddhh',($autoridades_ddhh)?$autoridades_ddhh->estado_actual:'');
                $template_fud->setValue('organismo_ddhh',($autoridades_ddhh)?$autoridades_ddhh->organismo:'');
                $template_fud->setValue('violacion_ddhh',($autoridades_ddhh)?$autoridades_ddhh->violacion_ddhh:'');
                $template_fud->setValue('tipo_resolucion',($autoridades_ddhh)?$autoridades_ddhh->tipo_resolucion:'');
                $template_fud->setValue('autoridades_otra',($autoridades_otra)?$autoridades_otra->nombre_otra_autoridad:'');
                $template_fud->setValue('grado_dependencia',$rev->grado_dependencia);
                $template_fud->setValue('tipo_discapacidad',$rev->tipo_discapacidad);
                $template_fud->setValue('tipo_institucion',$rev->tipo_institucion);
                $template_fud->setValue('tipo_medio_informativo',$rev->tipo_medio_informativo);
                $template_fud->setValue('nombre_medio_informativo',$rev->nombre_medio_informativo);
                $template_fud->setValue('entidad_salida',$rev->entidad_salida);
                $template_fud->setValue('entidad_receptora',$rev->entidad_receptora);
                $caracteres = array("[", "]", '"');
                $template_fud->setValue('hecho_victimizante_se_debio',str_replace($caracteres, "", $rev->hecho_victimizante_se_debio));
                $template_fud->setValue('informacion_violencia_contra_mujeres',str_replace($caracteres, "", $rev->informacion_violencia_contra_mujeres));
                
                ////////////
                $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');
                $template_fud->saveAs($tenpFile);

                $header = [
                    "Content-Type: application/octet-stream",
                ];
                return response()->download($tenpFile, 'FUD.docx', $header)->deleteFileAfterSend($shouldDelete = true);
                break;
            case FormatosEnum::CEDULA:
                $template_cedula = new TemplateProcessor(resource_path('CEDULA.docx'));
                $template_cedula->setValue('folio_expediente',$rev->folio_expediente);
                $template_cedula->setValue('nombre_completo_solicitante',$rev->solicitantes_nombres.' '.$rev->solicitantes_primer_apellido.' '.$rev->solicitantes_segundo_apellido);
                $template_cedula->setValue('nombre_completo_victima',$nombre_completo_victima);
                $template_cedula->setValue('lugar_atencion',$rev->lugar_atencion);
                $template_cedula->setValue('fecha_atencion',$fecha_atencion);
                $template_cedula->setValue('acuerdo_numero','');
                $template_cedula->setValue('nombre_superior_rev',$nombre_superior_rev->name);
                $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');
                $template_cedula->saveAs($tenpFile);
                $header = [
                    "Content-Type: application/octet-stream",
                ];
                return response()->download($tenpFile, 'CEDULA.docx', $header)->deleteFileAfterSend($shouldDelete = true);
                break;
            case FormatosEnum::ACUERDOS:
                $template_acuerdos = new TemplateProcessor(resource_path('ACUERDOS.docx'));
                $template_acuerdos->setValue('folio_expediente',$rev->folio_expediente);
                $template_acuerdos->setValue('nombre_completo_solicitante',$rev->solicitantes_nombres.' '.$rev->solicitantes_primer_apellido.' '.$rev->solicitantes_segundo_apellido);
                $template_acuerdos->setValue('nombre_completo_victima',$nombre_completo_victima);
                $template_acuerdos->setValue('lugar_atencion',$rev->lugar_atencion);
                $template_acuerdos->setValue('fecha_atencion',$fecha_atencion);
                $template_acuerdos->setValue('acuerdo_numero','');
                $template_acuerdos->setValue('nombre_superior_rev',$nombre_superior_rev->name);
                $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');
                $template_acuerdos->saveAs($tenpFile);

                $header = [
                    "Content-Type: application/octet-stream",
                ];
                return response()->download($tenpFile, 'ACUERDOS.docx', $header)->deleteFileAfterSend($shouldDelete = true);
                break;
        }
    }

    public function descargaCompleto($generales_id)
    {

        $documentos_escaner=DB::table('pc_entrevistas')->where('generales_id',$generales_id)->first();
        $solicitud_ingreso=$documentos_escaner->url_escaner_solicitud_ingreso;
        $fud=$documentos_escaner->url_escaner_fud;
        $acta_nacimiento=$documentos_escaner->url_escaner_acta_nacimiento;
        $curp=$documentos_escaner->url_escaner_curp;
        $iden_oficial=$documentos_escaner->url_escaner_iden_oficial;
        $comprobante_domicilio=$documentos_escaner->url_escaner_comprobante_domicilio;
        $calidad_victima=$documentos_escaner->url_escaner_calidad_victima;
        $cedula=$documentos_escaner->url_escaner_cedula;
        $noti_electronica=$documentos_escaner->url_escaner_noti_electronica;
        $acuerdo=$documentos_escaner->url_escaner_acuerdo;

        $pdf = new \Jurosh\PDFMerge\PDFMerger;
        
        // add as many pdfs as you want

        $merge=false;

        if ($solicitud_ingreso) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Solicitud de ingreso-'.$generales_id.'.pdf', 'all');
        }
        if ($fud) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/FUD-'.$generales_id.'.pdf', 'all');
        }
        if ($acta_nacimiento) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Acta de nacimiento-'.$generales_id.'.pdf', 'all');
        }
        if ($curp) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/CURP-'.$generales_id.'.pdf', 'all');
        }
        if ($iden_oficial) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Identificación Oficial-'.$generales_id.'.pdf', 'all');
        }
        if ($comprobante_domicilio) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Comprobante de domicilio-'.$generales_id.'.pdf', 'all');
        }
        if ($calidad_victima) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Constancia de calidad de víctima-'.$generales_id.'.pdf', 'all');
        }
        if ($cedula) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Cédula-'.$generales_id.'.pdf', 'all');
        }
        if ($noti_electronica) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Notificación electrónica-'.$generales_id.'.pdf', 'all');
        }
        if ($acuerdo) {
            $merge=true;
            $pdf->addPDF(storage_path() . '/app/public/escaner/Acuerdo-'.$generales_id.'.pdf', 'all');
        }

        if ($merge) {
            // call merge, output format `file`
            $pdf->merge('file', storage_path() . '/app/public/escaner/escaner-completo-'.$generales_id.'.pdf');

            $header = ["Content-Type: application/octet-stream",];

            $escanerCompleto= storage_path() . '/app/public/escaner/escaner-completo-'.$generales_id.'.pdf';

            return response()->download($escanerCompleto, 'escaner-completo-'.$generales_id.'.pdf', $header);
        }
        else{
            return false;
        }   
    }
}
