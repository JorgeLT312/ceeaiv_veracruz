<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Enums\EstatusAtencionEnum;
use App\Models\General;
use App\Models\VictimaDirecta;
use App\Models\VictimaIndirecta;
use App\Models\Delitos;
use App\Models\Sexo;
use App\Models\ViolacionDH;
use App\Models\IdentidadNac;
use App\Models\Municipios;
use App\Models\Ocupacion;
use App\Models\TipoSolicitante;
use App\Models\AmbitoCompetencia;
use App\Models\EnfoqueTipoDiscapacidad;
use App\Models\EnfoqueGradoDependencia;
use App\Models\EntidadFederativa;
use App\Models\DocumentoProbatorio;
use App\Models\DocProbatorioIdentidad;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \PDF;

class ReportesController extends Controller
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
        //Datos harcodeados
        //$startDate = Carbon::parse('2023-12-30'.' 00:00:00')->toDateTimeString('second');
        //$endDate = Carbon::parse('2024-01-31'.' 23:59:59')->toDateTimeString('second');
        //Asignación filtro estatus de atención
        //$request->estatus_atencion = '';
        $estatusAtencion = $request->estatus_atencion;
        if(empty($estatusAtencion)){
            $estatusAtencion = ['Captura Inicial', 'Primer Contacto', 'REV', 'RENAVI', 'No Aprobado'];
        }
  
        $capturas = General::select('generales.*',
            'tipo_victimas.nombre as nombre_tipo_victima',
            'victimas_directas.nombres',
            'victimas_directas.primer_apellido',
            'victimas_directas.segundo_apellido',
            'victimas_directas.fecha_nacimiento',
            'victimas_indirectas.nombres as indirecta_nombres',
            'victimas_indirectas.primer_apellido as indirecta_primer_apellido',
            'victimas_indirectas.segundo_apellido as indirecta_segundo_apellido',
            'victimas_indirectas.fecha_nacimiento',
            'capturas_iniciales.contacto_principal_nombres',
            'capturas_iniciales.contacto_principal_primer_apellido',
            'capturas_iniciales.contacto_principal_segundo_apellido',
            'capturas_iniciales.contacto_principal_correo',
            'capturas_iniciales.contacto_principal_telefono',
            'capturas_iniciales.contacto_secundario_nombres',
            'capturas_iniciales.contacto_secundario_primer_apellido',
            'capturas_iniciales.contacto_secundario_segundo_apellido',
            'capturas_iniciales.contacto_secundario_correo',
            'capturas_iniciales.contacto_secundario_telefono',
            'capturas_iniciales.generales_id',
            'pc_entrevistas.modalidad_atencion',
            'pc_entrevistas.registro_rev',
            'pc_entrevistas.updated_at as fecha_atencion',
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
            'victimas_directas.fecha_nacimiento as fnvd',
            'victimas_indirectas.fecha_nacimiento as fnvi')
                ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
                ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
                ->join('capturas_iniciales', 'generales.id', '=', 'capturas_iniciales.generales_id')
                ->join('pc_entrevistas', 'generales.id', '=', 'pc_entrevistas.generales_id')
                ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
                
                ->whereBetween('generales.updated_at', [$startDate, $endDate])
                /*->when($request->search, function($query, $search) {
                    $query->where('generales.no_rev', 'like', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                })*/
                ->when($request->search, function($query, $search) {
                     $query->where('generales.no_rev', 'like', "%$search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                    TRIM(victimas_directas.primer_apellido), " ", 
                                    TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                    TRIM(victimas_indirectas.primer_apellido), " ", 
                                    TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                })
                ->whereIn('generales.estatus_atencion', $estatusAtencion)
                ->latest()
                ->paginate()
                ->withQueryString();
        
        //Counts de victimas
        $totalVictimasDirectas = VictimaDirecta::leftJoin('generales', 'victimas_directas.generales_id', '=', 'generales.id')
                                                    ->whereIn('generales.estatus_atencion', $estatusAtencion)
                                                    ->when($request->search, function($query, $search) {
                                                         $query->where('generales.no_rev', 'like', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                                                        TRIM(victimas_directas.primer_apellido), " ", 
                                                                        TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                                                        TRIM(victimas_indirectas.primer_apellido), " ", 
                                                                        TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                                                    })
                                                    ->whereBetween('generales.updated_at', [$startDate, $endDate])
                                                    ->count();
        $totalVictimasIndirectas = VictimaIndirecta::leftJoin('generales', 'victimas_indirectas.generales_id', '=', 'generales.id')
                                                    ->whereIn('generales.estatus_atencion', $estatusAtencion)
                                                    ->when($request->search, function($query, $search) {
                                                         $query->where('generales.no_rev', 'like', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                                                        TRIM(victimas_directas.primer_apellido), " ", 
                                                                        TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                                                        TRIM(victimas_indirectas.primer_apellido), " ", 
                                                                        TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                                                    })
                                                    ->whereBetween('generales.updated_at', [$startDate, $endDate])
                                                    ->count();
        //Counts de victimas por estatus de atención
        $totalVictimasCapturaInicial = General::where('generales.estatus_atencion', 'Captura Inicial')
                                                    ->whereIn('generales.estatus_atencion', $estatusAtencion)

                                                    ->when($request->search, function($query, $search) {
                                                         $query->where('generales.no_rev', 'like', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                                                        TRIM(victimas_directas.primer_apellido), " ", 
                                                                        TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                                                        TRIM(victimas_indirectas.primer_apellido), " ", 
                                                                        TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                                                    })

                                                    ->whereBetween('generales.updated_at', [$startDate, $endDate])
                                                    ->count();
        $totalVictimasPrimerContacto = General::where('generales.estatus_atencion', 'Primer Contacto')
                                                    ->whereIn('generales.estatus_atencion', $estatusAtencion)

                                                    ->when($request->search, function($query, $search) {
                                                         $query->where('generales.no_rev', 'like', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                                                        TRIM(victimas_directas.primer_apellido), " ", 
                                                                        TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                                                        TRIM(victimas_indirectas.primer_apellido), " ", 
                                                                        TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                                                    })

                                                    ->whereBetween('generales.updated_at', [$startDate, $endDate])
                                                    ->count();
        $totalVictimasREV = General::where('generales.estatus_atencion', 'REV')
                                                    ->whereIn('generales.estatus_atencion', $estatusAtencion)

                                                    ->when($request->search, function($query, $search) {
                                                         $query->where('generales.no_rev', 'like', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                                                        TRIM(victimas_directas.primer_apellido), " ", 
                                                                        TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                                                        TRIM(victimas_indirectas.primer_apellido), " ", 
                                                                        TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                                                    })

                                                    ->whereBetween('generales.updated_at', [$startDate, $endDate])
                                                    ->count();
        $totalVictimasRENAVI = General::where('generales.estatus_atencion', 'RENAVI')
                                                    ->whereIn('generales.estatus_atencion', $estatusAtencion)

                                                    ->when($request->search, function($query, $search) {
                                                         $query->where('generales.no_rev', 'like', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                                                        TRIM(victimas_directas.primer_apellido), " ", 
                                                                        TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere(DB::raw(
                                                                'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                                                        TRIM(victimas_indirectas.primer_apellido), " ", 
                                                                        TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                                                            ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                                                            ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                                                    })

                                                    ->whereBetween('generales.updated_at', [$startDate, $endDate])
                                                    ->count();
        //Algoritmo de counts de victimas Adultos y NNA por estatus de atención                             
        $adultosCI = 0;
        $NNACI = 0;
        $adultosPC = 0;
        $NNAPC = 0;
        $adultosREV = 0;
        $NNAREV = 0;
        $adultosRENAVI = 0;
        $NNARENAVI = 0;
        foreach($capturas as $victima){
            if($victima->estatus_atencion === 'Captura Inicial'){
                if($victima->fnvd != null){
                    $edad = Carbon::createFromDate($victima->fnvd)->age;
                    if($edad > 17){
                        $adultosCI = $adultosCI + 1;
                    }else{
                        $NNACI = $NNACI + 1;
                    }
                }
                if($victima->fnvi != null){
                    $edad = Carbon::createFromDate($victima->fnvi)->age;
                    if($edad > 17){
                        $adultosCI = $adultosCI + 1;
                    }else{
                        $NNACI = $NNACI + 1;
                    }
                }
            }
            if($victima->estatus_atencion === 'Primer Contacto'){
                if($victima->fnvd != null){
                    $edad = Carbon::createFromDate($victima->fnvd)->age;
                    if($edad > 17){
                        $adultosPC = $adultosPC + 1;
                    }else{
                        $NNAPC = $NNAPC + 1;
                    }
                }
                if($victima->fnvi != null){
                    $edad = Carbon::createFromDate($victima->fnvi)->age;
                    if($edad > 17){
                        $adultosPC = $adultosPC + 1;
                    }else{
                        $NNAPC = $NNAPC + 1;
                    }
                }
            }
            if($victima->estatus_atencion === 'REV'){
                if($victima->fnvd != null){
                    $edad = Carbon::createFromDate($victima->fnvd)->age;
                    if($edad > 17){
                        $adultosREV = $adultosREV + 1;
                    }else{
                        $NNAREV = $NNAREV + 1;
                    }
                }
                if($victima->fnvi != null){
                    $edad = Carbon::createFromDate($victima->fnvi)->age;
                    if($edad > 17){
                        $adultosREV = $adultosREV + 1;
                    }else{
                        $NNAREV = $NNAREV + 1;
                    }
                }
            }
            if($victima->estatus_atencion === 'RENAVI'){
                if($victima->fnvd != null){
                    $edad = Carbon::createFromDate($victima->fnvd)->age;
                    if($edad > 17){
                        $adultosRENAVI = $adultosRENAVI + 1;
                    }else{
                        $NNARENAVI = $NNARENAVI + 1;
                    }
                }
                if($victima->fnvi != null){
                    $edad = Carbon::createFromDate($victima->fnvi)->age;
                    if($edad > 17){
                        $adultosRENAVI = $adultosRENAVI + 1;
                    }else{
                        $NNARENAVI = $NNARENAVI + 1;
                    }
                }
            }
        }

        //Delitos
        /*$delitos = General::select('generales.*',
            'autoridades.delitos_id as delito',
            'hechos_victimizantes.delitos_id as delitos')
                ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
                ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
                ->join('autoridades', 'generales.id', '=', 'autoridades.generales_id')
                ->join('hechos_victimizantes', 'generales.id', '=', 'hechos_victimizantes.generales_id')
                ->whereBetween('generales.updated_at', [$startDate, $endDate])
                ->when($request->search, function($query, $search) {
                    $query->where('generales.no_rev', 'like', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                })
                ->whereIn('generales.estatus_atencion', $estatusAtencion)
                ->get();

        $data = [];
        //Se obtienen todos los delitos
        foreach($delitos as $delito){
            $name = Delitos::where('clave', $delito->delito)->first();
            if($name != null){
                $data[] = array("id" => $delito->delito, "nombre" => $name->nombre);
            }
            
            $delitos_array = json_decode($name);
            if($delitos_array != null){
                foreach($delitos_array as $id){
                    $name = Delitos::where('clave', $id)->first();
                    if ($name) {
                        $data[] = array("id" => $id, "nombre" => $name->nombre);
                    }
                }
            }
        }
        //Se cuentan valores basados en ID
        $counts = array_count_values(array_column($data, 'id'));
        //Se crea nuevo array con ID, nombre de delito y total de counts
        $delitos_final = [];
        foreach($data as $d){
            foreach($counts as $key => $count){
                if($d['id'] == $key){
                    $delitos_final[] = array("id" => $key, "nombre" => $d['nombre'], "count" => $count);
                }
            }
        }
        //Se quitan delitos duplicados
        $delitos_counts = array_unique($delitos_final,SORT_REGULAR);*/



        $delitos_counts = DB::table('generales')

        $delitos_counts =  DB::table('generales')
            ->whereIn('generales.estatus_atencion', $estatusAtencion)
            ->whereBetween('generales.updated_at', [$startDate, $endDate])

            ->selectRaw('delitos.delitos_id as id,delitos.nombre, count(*) as count')
            ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
            ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
            ->leftJoin('autoridades', 'generales.id', '=', 'autoridades.generales_id')
            ->join('hechos_victimizantes', 'generales.id', '=', 'hechos_victimizantes.generales_id')
            ->join('tbl_victimizante_hechos_mm_delitos', 'hechos_victimizantes.victimizante_hechos_id', '=', 'tbl_victimizante_hechos_mm_delitos.victimizante_hechos_id')
            ->join('delitos', 'delitos.delitos_id', '=', 'tbl_victimizante_hechos_mm_delitos.delitos_id')
            ->groupBy('delitos.delitos_id','delitos.nombre')
            ->whereBetween('generales.updated_at', [$startDate, $endDate])
                /*->when($request->search, function($query, $search) {
                    $query->where('generales.no_rev', 'like', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                })*/
                ->when($request->search, function($query, $search) {
                     $query->where('generales.no_rev', 'like', "%$search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                    TRIM(victimas_directas.primer_apellido), " ", 
                                    TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                    TRIM(victimas_indirectas.primer_apellido), " ", 
                                    TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%");
                })
                ->whereIn('generales.estatus_atencion', $estatusAtencion)
            ->orderBy('delitos.nombre','asc')
            ->get();

        $filters = $request->only(['search']);

        return Inertia::render('Reportes', compact(
            'capturas', 
            'filters', 
            'totalVictimasDirectas', 
            'totalVictimasIndirectas',
            'totalVictimasCapturaInicial',
            'totalVictimasPrimerContacto',
            'totalVictimasREV',
            'totalVictimasRENAVI',
            'adultosCI',
            'adultosPC',
            'adultosREV',
            'adultosRENAVI',
            'NNACI',
            'NNAPC',
            'NNAREV',
            'NNARENAVI',
            'delitos_counts'
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function imprimirReporte($generales_id)
    {
        $captura=DB::table('generales')
            ->where('generales.id',$generales_id)
            ->join('capturas_iniciales', 'generales.id', '=', 'capturas_iniciales.generales_id')
            ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
            ->join('pc_entrevistas', 'pc_entrevistas.generales_id', '=', 'generales.id')
            ->join('datos_solicitantes', 'datos_solicitantes.generales_id', '=', 'generales.id')
            ->join('hechos_victimizantes', 'hechos_victimizantes.generales_id', '=', 'generales.id')
            ->join('domicilio_notificaciones', 'domicilio_notificaciones.generales_id', '=', 'generales.id')
            ->join('factores_vulnerabilidades', 'factores_vulnerabilidades.generales_id', '=', 'generales.id')
            ->leftjoin('enfoque_grado_dependencia','enfoque_grado_dependencia.enfoque_grado_dependencia_id','=','factores_vulnerabilidades.grado_dependencia')
            ->leftjoin('tipos_telefonos','tipos_telefonos.tipos_telefonos_id','=','domicilio_notificaciones.tipos_telefonos_id')
            ->leftJoin('registros_estatales', 'registros_estatales.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_directas', 'victimas_directas.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_indirectas', 'victimas_indirectas.generales_id', '=', 'generales.id')
            ->leftJoin('users', 'users.id', '=', 'pc_entrevistas.atencion_psicologica_psicologo_id')
            ->leftjoin('documentos_probatorios','documentos_probatorios.documentos_probatorios_id','=','victimas_directas.tipo_identificacion_id')
            ->select('pc_entrevistas.*', 'generales.*',
                'tipo_victimas.nombre as nombre_tipo_victima',
                'capturas_iniciales.contacto_principal_nombres',
                'capturas_iniciales.contacto_principal_primer_apellido',
                'capturas_iniciales.contacto_principal_segundo_apellido',
                'capturas_iniciales.contacto_principal_correo',
                'capturas_iniciales.contacto_principal_telefono',
                'capturas_iniciales.contacto_secundario_nombres',
                'capturas_iniciales.contacto_secundario_primer_apellido',
                'capturas_iniciales.contacto_secundario_segundo_apellido',
                'capturas_iniciales.contacto_secundario_correo',
                'capturas_iniciales.contacto_secundario_telefono',
                'capturas_iniciales.generales_id',
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
                'datos_solicitantes.situaciones_migratorias_id as solicitantes_situacion_migratoria_id',
                'datos_solicitantes.cargo',
                'datos_solicitantes.sexos_id as sexo_solicitante',
                'hechos_victimizantes.territorios_id as hechos_territorios_id',
                'hechos_victimizantes.paises_id as hechos_paises_id',
                'hechos_victimizantes.entidades_federativas_id as hechos_entidades_federativas_id',
                'hechos_victimizantes.municipios_id as hechos_municipios_id',
                'hechos_victimizantes.poblacion as hechos_poblacion',
                'hechos_victimizantes.relato',
                'hechos_victimizantes.conoces_lugar_hechos',
                'hechos_victimizantes.lo_que_conoces_lugar_hechos',
                'hechos_victimizantes.fecha_hecho_ini',
                'hechos_victimizantes.fecha_hecho_fin',
                'hechos_victimizantes.calle as hecho_calle',
                'hechos_victimizantes.num_exterior as hecho_num_exterior',
                'hechos_victimizantes.num_interior as hecho_num_interior',
                'hechos_victimizantes.colonia as hecho_colonia',
                'hechos_victimizantes.codigo_postal as hecho_codigo_postal',
                'hechos_victimizantes.localidad as hecho_localidad',
                'victimas_directas.nombres as victimas_nombres',
                'victimas_directas.primer_apellido as victimas_primer_apellido',
                'victimas_directas.segundo_apellido as victimas_segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                'victimas_directas.no_carpeta',
                'victimas_directas.fiscalia',
                'victimas_directas.poblacion as victima_directa_poblacion',
                'victimas_directas.curp',
                'victimas_directas.sexos_id as victima_sexos_id',
                'victimas_directas.presenta_identificacion',
                'victimas_directas.territorios_id as victimas_directas_territorio',
                'victimas_directas.entidades_federativas_id as victimas_directas_entidades_federativas',
                'victimas_directas.municipios_id as victimas_directas_municipios',
                'victimas_directas.delitos_id as delito_provicional',
                'victimas_directas.violaciones_id as violacion_provicional',
                'victimas_directas.identidad_probatorio_documentos_id',
                'victimas_directas.tipo_identificacion_id',
                'victimas_directas.estado_civil_id',
                'victimas_indirectas.nombres as victimas_indirectas_nombres',
                'victimas_indirectas.primer_apellido as victimas_indirectas_primer_apellido',
                'victimas_indirectas.segundo_apellido as victimas_nidirectas_segundo_apellido',
                'victimas_indirectas.curp as victimas_indirectas_curp',
                'victimas_indirectas.territorios_id as victimas_indirectas_territorio',
                'victimas_indirectas.entidades_federativas_id as victimas_indirectas_entidades_federativas',
                'victimas_indirectas.municipios_id as victimas_indirectas_municipios',
                'victimas_indirectas.poblacion as victima_indirecta_poblacion',
                'hechos_victimizantes.delitos_id',
                'hechos_victimizantes.violaciones_id',
                'hechos_victimizantes.ambito_competencia',
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
                'enfoque_grado_dependencia.nombre as grado_dependencia',
                'tipos_telefonos.nombre as tipo_telefono',
                'documentos_probatorios.nombre as tipo_documento',
                'users.name as nombre_psicologo',
                )
            ->first();
            $sexo_solicitante_nombre=Sexo::where('sexos_id',$captura->sexo_solicitante)->first();
            $delito =Delitos::where('delitos_id',$captura->delitos_id)->first();
            $delito_provicional =Delitos::where('delitos_id',$captura->delito_provicional)->first();
            $violacion = ViolacionDH::where('violaciones_id',$captura->violaciones_id)->first(); 
            $violacion_provicional = ViolacionDH::where('violaciones_id',$captura->violacion_provicional)->first(); 
            
            $tipo_solicitante= TipoSolicitante::where('tipo_solicitantes_id',$captura->tipo_solicitantes_id)->first();
            $ambito_competencia = AmbitoCompetencia::where('id',$captura->ambito_competencia)->first();
            $tipo_discapacidad = EnfoqueTipoDiscapacidad::where('id',$captura->tipo_discapacidad)->first();

            $nacionalidad_solicitantes = IdentidadNac::where('territorios_id',$captura->solicitantes_territorios_id)->first();
            $entidad_solicitantes = EntidadFederativa::where('entidades_federativas_id',$captura->solicitantes_entidades_federativas_id)->first();
            $municipio_solicitantes = Municipios::where('municipios_id',$captura->solicitantes_municipios_id)->first();

            $nacionalidad_hechos = IdentidadNac::where('territorios_id',$captura->hechos_territorios_id)->first();
            $entidad_hechos = EntidadFederativa::where('entidades_federativas_id',$captura->hechos_entidades_federativas_id)->first();
            $municipio_hechos = Municipios::where('municipios_id',$captura->hechos_municipios_id)->first();

            $nacionalidad_notificaciones = IdentidadNac::where('territorios_id',$captura->notificaciones_territorios_id)->first();
            $entidad_notificaciones = EntidadFederativa::where('entidades_federativas_id',$captura->notificaciones_entidades_federativas_id)->first();
            $municipio_notificaciones = Municipios::where('municipios_id',$captura->notificaciones_municipios_id)->first();

            $nacionalidad_victima = IdentidadNac::where('territorios_id',($captura->victimas_directas_territorio)?$captura->victimas_directas_territorio:$captura->victimas_indirectas_territorio)->first();
            $entidad_victima = EntidadFederativa::where('entidades_federativas_id',($captura->victimas_directas_entidades_federativas)?$captura->victimas_directas_entidades_federativas:$captura->victimas_indirectas_entidades_federativas)->first();
            $municipio_victima = Municipios::where('municipios_id',($captura->victimas_directas_municipios)?$captura->victimas_directas_municipios:$captura->victimas_indirectas_municipios)->first();

            $ocupaciones_victima=DB::table('ocupaciones')->where('ocupaciones_id',$captura->victimas_ocupaciones_id)->first();
            $ocupacion_notificaciones = DB::table('ocupaciones')->where('ocupaciones_id',$captura->notificaciones_ocupaciones_id)->first();

            $estados_civil=DB::table('estados_civil')->where('id',$captura->estado_civil_id)->first();

            $familiares=DB::table('familiares')
            ->select('familiares.*','parentescos.nombre as parentesco')
            ->join('parentescos','parentescos.parentescos_id','familiares.parentesco_id')
            ->where('generales_id',$generales_id)
            ->get();

            $autoridades = DB::table('autoridades')
            ->select('autoridades.*','delitos.nombre as delitos_autoridades','violaciones.nombre as violaciones_autoridades','danios_sufridos.nombre as danio_autoridad','entidades_federativas.name as nombre_entidades')
            ->leftJoin('delitos','delitos.delitos_id','autoridades.delitos_id')
            ->leftJoin('violaciones','violaciones.violaciones_id','autoridades.violaciones_id')
            ->leftJoin('danios_sufridos','danios_sufridos.danios_sufridos_id','autoridades.tipo_danio_id')
            ->leftJoin('entidades_federativas','entidades_federativas.entidades_federativas_id','=','autoridades.entidades_federativas_id')
            ->where('generales_id',$generales_id)
            ->get();

            $documentos_probatorios_ids=explode(',',substr($captura->identidad_probatorio_documentos_id,1,-1));
            $documentos_probatorios='';
            foreach ($documentos_probatorios_ids as $documento_probatorio_id) {
                $documentos = DocProbatorioIdentidad::where('identidad_probatorio_documentos_id',$documento_probatorio_id)->first();
                ($documentos)?$documentos_probatorios=$documentos_probatorios.' / '.$documentos->nombre:'';
            }
            
        $renavi='';
        /*return View::make('reporteVictima')->with([
            'captura'=>$captura,'sexo_solicitante'=>$sexo_solicitante,
            'delito'=>$delito,'violacion'=>$violacion,'nacionalidad_notificaciones'=>$nacionalidad_notificaciones,
            'ocupacion_notificaciones'=>$ocupacion_notificaciones,'tipo_solicitante'=>$tipo_solicitante,
            'nacionalidad_solicitantes'=>$nacionalidad_solicitantes,'nacionalidad_hechos'=>$nacionalidad_hechos,
            'autoridades'=>$autoridades,'ambito_competencia'=>$ambito_competencia,'tipo_discapacidad'=>$tipo_discapacidad,
            'nacionalidad_victima'=>$nacionalidad_victima,'entidad_victima'=>$entidad_victima
        ], $captura);*/
        $caracteres = array("[", "]", '"');
        $acentos=array('\u00f3');
        $solicitudes_opciones=str_replace($caracteres, "", $captura->solicitudes_ayuda_opciones);
        $solicitudes_ayuda_opciones=str_replace($acentos, "ó", $solicitudes_opciones);
        $juridica_opciones=str_replace($caracteres,"",$captura->asesoria_juridica_opciones);
        $asesoria_juridica_opciones=str_replace($caracteres,"",$juridica_opciones);
        $pdf = \PDF::loadView('reporteVictima',compact(['captura','sexo_solicitante_nombre','delito','violacion','nacionalidad_notificaciones','ocupacion_notificaciones','tipo_solicitante','nacionalidad_hechos','nacionalidad_solicitantes','autoridades','ambito_competencia',
            'tipo_discapacidad','nacionalidad_victima','entidad_victima','municipio_victima','entidad_solicitantes','municipio_solicitantes','entidad_hechos','municipio_hechos','ocupaciones_victima','delito_provicional','violacion_provicional','familiares','entidad_notificaciones','municipio_notificaciones','documentos_probatorios','estados_civil','solicitudes_ayuda_opciones','asesoria_juridica_opciones']));
        return $pdf->download('ReporteVictimas.pdf');
    }
}
