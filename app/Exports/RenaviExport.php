<?php

namespace App\Exports;

use App\Models\General;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\DocProbatorioIdentidad;
use App\Models\AmbitoDependencia;
use App\Enums\EstatusAtencionEnum;
use Carbon\Carbon;

class RenaviExport implements FromCollection, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($datos)
    {
        $this->search = $datos->search;
        $this->startDate = $datos->startDate;
        $this->endDate = $datos->endDate;
        $this->estatus_atencion = $datos->estatus_atencion;
        $this->datos_pendientes = $datos->datos_pendientes;
    }

    public function collection()
    {
        $coleccion = array();
        $search=$this->search;
        $estatus_atencion=$this->estatus_atencion;
        $datos_pendientes=$this->datos_pendientes;

        $startDate = Carbon::parse($this->startDate.' 00:00:00')->toDateTimeString('second');
        $endDate = Carbon::parse($this->endDate.' 23:59:59')->toDateTimeString('second');
        if(empty($this->startDate)){
            $startDate = Carbon::now()->subMonth(1)->startOfDay()->toDateTimeString('second');
        }
        $consulta=DB::table('generales')
            ->join('pc_entrevistas', 'pc_entrevistas.generales_id', '=', 'generales.id')
            ->join('datos_solicitantes', 'datos_solicitantes.generales_id', '=', 'generales.id')
            ->join('hechos_victimizantes', 'hechos_victimizantes.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_directas', 'victimas_directas.generales_id', '=', 'generales.id')
            ->leftJoin('victimas_indirectas', 'victimas_indirectas.generales_id', '=', 'generales.id')
            ->Join('registros_estatales', 'registros_estatales.generales_id', '=', 'generales.id')
            ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
            ->join('domicilio_notificaciones', 'domicilio_notificaciones.generales_id', '=', 'generales.id')
            ->join('factores_vulnerabilidades', 'factores_vulnerabilidades.generales_id', '=', 'generales.id')
            ->leftjoin('documentos_probatorios','documentos_probatorios.documentos_probatorios_id','=','victimas_directas.tipo_identificacion_id')
            ->select('pc_entrevistas.*', 'generales.*','generales.id as generales',
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
                'victimas_directas.nombres as nombres',
                'victimas_directas.primer_apellido as primer_apellido',
                'victimas_directas.segundo_apellido as segundo_apellido',
                'victimas_directas.fecha_nacimiento as victimas_fecha_nacimiento',
                'victimas_directas.ocupaciones_id as victimas_ocupaciones_id',
                'victimas_directas.delitos_id',
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
                'victimas_indirectas.nombres as indirecta_nombres',
                'victimas_indirectas.primer_apellido as indirecta_primer_apellido',
                'victimas_indirectas.segundo_apellido as indirecta_segundo_apellido',
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
                'tipo_victimas.nombre as nombre_tipo_victima',
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
                'documentos_probatorios.nombre as tipo_documento',
                )
                ->whereBetween('generales.updated_at', [$startDate, $endDate])
                ->where(function ($query) use ($search){
                    $query->where('generales.no_rev', 'like', "%$search%")
                        ->orWhere('generales.no_renavi', 'like', "%$search%")
                        ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                        ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%")
                        ->orWhere('tipo_victimas.nombre', 'like', "%$search%");
                })
                ->where(function ($query) {
                    $query->where('generales.estatus_atencion',EstatusAtencionEnum::REV)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::REV_SIN_PC)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::EN_ESPERA_RENAVI)
                        ->orwhere('generales.estatus_atencion',EstatusAtencionEnum::RENAVI);
                })
                ->where(function ($query) use ($estatus_atencion){
                    if ($estatus_atencion) {
                        $query->where('generales.estatus_atencion',$estatus_atencion);
                    }
                })
                ->when($datos_pendientes != null, function ($query) use ($datos_pendientes) {
                    $query->where('generales.datos_completo_renavi',$datos_pendientes);
                })->get();
        foreach ($consulta as $dato) {
            $sexo=DB::table('sexos')->where('sexos_id',($dato->sexos_id)?$dato->sexos_id:$dato->victimas_indirectas_sexos_id)->first();

            $nacionalidad=DB::table('territorios')
                ->where('territorios_id',($dato->territorios_id)?$dato->territorios_id:$dato->victimas_indirectas_territorios_id)
                ->first();

            $entidad=DB::table('entidades_federativas')
                ->where('entidades_federativas_id',($dato->entidades_federativas_id)?$dato->entidades_federativas_id:$dato->victimas_indirectas_entidades_federativas_id)
                ->first();

            $municipio=DB::table('municipios')
                ->where('municipios_id',($dato->municipios_id)?$dato->municipios_id:$dato->victimas_indirectas_municipios_id)
                ->first();
            
            $pais=DB::table('paises')
                ->where('paises_id',($dato->paises_id)?$dato->paises_id:$dato->victimas_indirectas_paises_id)
                ->first();

            $sexo_solicitante=DB::table('sexos')->where('sexos_id',$dato->solicitantes_sexos_id)->first();

            $ambito_dependencia=DB::table('ambito_dependencias')
                ->where('ambito_dependencias_id',$dato->solicitante_ambito_dependencias_id)->first();

            $delito=DB::table('delitos')
                ->where('delitos_id',$dato->delitos_id)->first();

            $documentos_probatorios_ids=explode(',',substr($dato->identidad_probatorio_documentos_id,1,-1));
            $documentos_probatorios='';
            foreach ($documentos_probatorios_ids as $documento_probatorio_id) {
                $documentos = DocProbatorioIdentidad::where('identidad_probatorio_documentos_id',$documento_probatorio_id)->first();
                ($documentos)?$documentos_probatorios=$documentos_probatorios.' / '.$documentos->nombre:'';
            }

            $direccion='Calle: '.$dato->calle.' No. Exterior:'.$dato->no_exterior.' No. Interior:'.$dato->no_interior.' Colonia:'.$dato->colonia.' CP.:'.$dato->codigo_postal.' Localidad:'.$dato->localidad;

            $autoridades = DB::table('autoridades')
            ->where('generales_id',$dato->generales)
            ->get();
            $nombre_autoridad='';
            $nombre_institucion='';
            foreach ($autoridades as $autoridad) {
                $nombre_autoridad=$nombre_autoridad.' / '.$autoridad->nombre_autoridad;
                $nombre_institucion=$nombre_institucion.' / '.$autoridad->institucion;
            }
            $ambito_dependencia = AmbitoDependencia::where('ambito_dependencias_id',$dato->solicitante_ambito_dependencias_id)->first();

            array_push($coleccion,[
                ($dato->nombres)?$dato->nombres:$dato->indirecta_nombres, 
                ($dato->primer_apellido)?$dato->primer_apellido:$dato->indirecta_primer_apellido,
                ($dato->segundo_apellido)?$dato->segundo_apellido:$dato->indirecta_segundo_apellido, 
                $dato->nombre_tipo_victima,
                ($sexo)?$sexo->nombre:'', 
                ($dato->curp)?$dato->curp:$dato->victimas_indirectas_curp,
                ($dato->victimas_fecha_nacimiento)?$dato->victimas_fecha_nacimiento:$dato->victimas_indirectas_fecha_nacimiento, 
                ($nacionalidad)?$nacionalidad->nombre:'',
                ($entidad)?$entidad->name:'', 
                ($municipio)?$municipio->name:'',
                ($pais)?$pais->nombre:'', 
                $dato->solicitantes_nombres,
                $dato->solicitantes_primer_apellido, 
                $dato->solicitantes_segundo_apellido,
                ($sexo_solicitante)?$sexo_solicitante->nombre:'', 
                ($dato->solicitante_fecha_nacimiento)?$dato->solicitante_fecha_nacimiento:'',
                $direccion,//dirección 
                ($delito)?$delito->nombre:'',
                $dato->hechos_relato, 
                $dato->tipo_documento,//identificacion
                $documentos_probatorios, 
                $nombre_autoridad,//Nombre Autoridad 
                $nombre_institucion,//Nombre Institución  
                $ambito_dependencia,//Ambito Dependencia
                $dato->grado_dependencia,//nivel dependencia
                ($dato->es_adolescente==1)?'Si':'No', 
                ($dato->es_adulto_mayor==1)?'Si':'No',
                ($dato->es_situacion_calle==1)?'Si':'No', 
                ($dato->es_discapacitado==1)?'Si':'No',
                ($dato->es_migrante==1)?'Si':'No', 
                ($dato->cual_poblacion_indigena==1)?'Si':'No',
                ($dato->es_refugiado==1)?'Si':'No', 
                ($dato->es_asilado_politico==1)?'Si':'No',
                ($dato->es_defensor_ddhh==1)?'Si':'No',
                ($dato->pertenece_instutitucion==1)?'Si':'No',
                ($dato->es_periodista==1)?'Si':'No',
                ($dato->es_desplazado_por_hecho==1)?'Si':'No',
                $dato->observaciones,

            ]);
        }
        return collect([$coleccion]);
    }

    public function headings(): array
    {
         return [
                'Nombres',
                'Primer Apellido',
                'Segundo Apellido',  
                'Tipo Victima', 
                'Sexo',   
                'Curp', 
                'Fecha Nacimiento',   
                'Terrirorio Nacimiento',  
                'Entidad Nacimiento',
                'Municipio Nacimiento',   
                'PaÍs Nacimiento', 
                'Nombres Persona Confianza' ,  
                'Primer Apellido Persona Confianza',
                'Segundo Apellido Persona Confianza',
                'Sexo Persona Confianza',
                'Fecha Nacimiento Confianza', 
                'Dirección Confianza', 
                'Delito',   
                'Relato', 
                'Identificaciones',
                'Documentos Probatorios',  
                'Nombre Autoridad',  
                'Nombre Institución', 
                'Ambito Dependencia', 
                'Nivel Dependencia',  
                'Es Adolecente',  
                'Es AdultoMayor', 
                'Es SituacionCalle',   
                'Es Discapacitado',
                'Es Migrante', 
                'Es Población Indigena', 
                'Es Refugiado', 
                'Es Asilado', 
                'Es Defensor DDHH',  
                'Es Institucion DDHH', 
                'Es Periodista',  
                'Es Desplazado',   
                'Consideraciones'
            ];

    }

    public function title(): string
    {
        return 'SetDatos';
    }
}