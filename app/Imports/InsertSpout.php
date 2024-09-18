<?php

namespace App\Imports;

use App\Enums\CatEventosEnum;
use App\Helpers\SpoutHelper;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\TipoVictima;
use App\Models\VictimaDirecta;
use App\Models\VictimaIndirecta;
use App\Models\PrimerContacto;
use App\Models\CapturaInicial;
use App\Models\General;
use App\Models\HechosVictimizantes;
use App\Models\Solicitante;
use App\Models\DomicilioNotificaciones;
use App\Models\FactoresVulnerabilidades;
use App\Enums\TipoVictimaEnum;
use App\Models\Parentesco;
use App\Models\Familiares;
use App\Models\Delitos;
use App\Models\ViolacionDH;
use App\Models\Sexo;
use App\Models\RegistrosEstatales;
use App\Models\TipoSolicitante;
use App\Models\Folios;
use App\Models\FoliosRev;

class InsertSpout {

    public function __construct(){
        $this->insertar();
    }

    private function insertar()
    {
        $rutaArchivo = storage_path() . '/app/public/excel_carga/excel-masivo.xlsx';
        $reader = ReaderEntityFactory::createReaderFromFile($rutaArchivo);
        $reader->open($rutaArchivo);
        foreach ($reader->getSheetIterator() as $sheet) {
            $spoutHelper = new SpoutHelper($sheet, 1);
            DB::beginTransaction();
            $datos=array();
            foreach ($sheet->getRowIterator() as $numero_fila => $row) {
                if ($numero_fila == 1) {
                    continue;
                }
                
                $rowH = $spoutHelper->rowWithRawHeaders($row->toArray());

                /*$tieneSaltosDeLinea = strpos($rowH['Nombre(s)'], "\n") !== false || strpos($rowH['Nombre(s)'], "\r") !== false;

                if ($tieneSaltosDeLinea) {
                    \Log::info('->'.$rowH['Nombre(s)'].'<-  '.'si tiene saltos');
                } else {
                    \Log::info($rowH['Nombre(s)'].'  '.'no tiene saltos');
                }*/

                if (!empty($rowH['Nombre(s)']) || !empty($rowH['Primer apellido']) || !empty($rowH['Segundo apellido'])) {
                    $parentesco_id=$this->getParentesco($rowH['Parentesco con VD']);
                    $delito_id=$this->getDelito($rowH['Delito']);
                    $violacion_id=$this->getViolacion($rowH['Violación DDHH']);
                    $sexo_id=$this->getSexo($rowH['Genero']);

                    if ($rowH['Tipo de víctima']=='Directa') {
                        $tipo_victimas_id=TipoVictimaEnum::DIRECTA;
                        $tipo_solicitante=$this->getTipoSolicitante('VICTIMA');
                    }
                    else{
                        $tipo_victimas_id=TipoVictimaEnum::INDIRECTA;
                        $tipo_solicitante=$this->getTipoSolicitante('FAMILIAR O PERSONA DE CONFIANZA');
                    }

                    $folio=explode('/', $rowH['Número de expediente']);

                    $folio_rev=explode('/', $rowH['Número de registro']);

                    if(count($folio_rev)==4){
                        $folio_digitos=(strlen($folio_rev[2]) < 5) ? $folio_cast=str_pad($folio_rev[2], 5, "0", STR_PAD_LEFT) : $folio_cast=$folio_rev[2];
                        $num_registro=$folio_rev[0].'/'.$folio_rev[1].'/'.$folio_digitos.'/'.$folio_rev[2];
                    }

                    if(count($folio)==3){
                        $folio_digitos=(strlen($folio[1]) < 5) ? $folio_cast=str_pad($folio[1], 5, "0", STR_PAD_LEFT) : $folio_cast=$folio[1];
                        $num_expediente=$folio[0].'/'.$folio_digitos.'/'.$folio[2];
                    }
                    $general = new General;
                    $general->no_rev = $num_registro;
                    $general->no_renavi = $num_expediente;
                    $general->estatus_atencion = ($rowH['Estatus de atención'] == 'Rev' || $rowH['Estatus de atención'] == 'Renavi')? strtoupper($rowH['Estatus de atención']):$rowH['Estatus de atención'];
                    $general->tipo_victimas_id = $tipo_victimas_id;
                    $general->save();

                    if ($tipo_victimas_id==TipoVictimaEnum::DIRECTA) {
                        $victimaDirecta = new VictimaDirecta;
                        $victimaDirecta->nombres = $rowH['Nombre(s)'];
                        $victimaDirecta->primer_apellido = $rowH['Primer apellido'];
                        $victimaDirecta->segundo_apellido = $rowH['Segundo apellido'];
                        $victimaDirecta->delitos_id = ($delito_id)?$delito_id->delitos_id:null;
                        $victimaDirecta->sexos_id = ($sexo_id)?$sexo_id->sexos_id:null;
                        $victimaDirecta->generales_id = $general->id;
                        $victimaDirecta->save();

                        CapturaInicial::where('generales_id', $general->id)
                        ->update([
                            'victimas_directas_id' => $victimaDirecta->victimas_directas_id,
                        ]);
                    }
                    else{
                        $victimaIndirecta = new VictimaIndirecta;
                        $victimaIndirecta->nombres = $rowH['Nombre(s)'];
                        $victimaIndirecta->primer_apellido = $rowH['Primer apellido'];
                        $victimaIndirecta->segundo_apellido = $rowH['Segundo apellido'];
                        $victimaIndirecta->parentescos_id = ($parentesco_id)?$parentesco_id->parentescos_id:null;
                        $victimaIndirecta->sexos_id = ($sexo_id)?$sexo_id->sexos_id:null;
                        $victimaIndirecta->generales_id = $general->id;
                        $victimaIndirecta->save();

                        CapturaInicial::where('generales_id', $general->id)
                        ->update([
                            'victimas_indirectas_id' => $victimaIndirecta->victimas_indirectas_id,
                        ]);

                        $victimaDirecta = new VictimaDirecta;
                        $victimaDirecta->nombres = '-';
                        $victimaDirecta->primer_apellido = '-';
                        $victimaDirecta->segundo_apellido = '-';
                        $victimaDirecta->generales_id = $general->id;
                        $victimaDirecta->save();
                    }
                
                    $solicitante = new Solicitante;
                    $solicitante->nombres = $rowH['Nombre(s)'];
                    $solicitante->primer_apellido = $rowH['Primer apellido'];
                    $solicitante->segundo_apellido = $rowH['Segundo apellido'];
                    $solicitante->sexos_id = ($sexo_id)?$sexo_id->sexos_id:null;
                    $solicitante->generales_id = $general->id;
                    $solicitante->save();
                
                    $hechos_victimizante = new HechosVictimizantes;
                    $hechos_victimizante->generales_id = $general->id;
                    $hechos_victimizante->delitos_id = ($delito_id)?json_encode([$delito_id->delitos_id]):null;
                    $hechos_victimizante->violaciones_id = ($violacion_id)?$violacion_id->violaciones_id:null;
                    $hechos_victimizante->save();

                    $id_victimizante_hechos = DB::table('hechos_victimizantes')->where('generales_id',$general->id)->first();
                    DB::table('tbl_victimizante_hechos_mm_delitos')
                        ->where('victimizante_hechos_id', $id_victimizante_hechos->victimizante_hechos_id)
                        ->delete();
                    if ($delito_id) {
                        DB::table('tbl_victimizante_hechos_mm_delitos')->insert([
                            'victimizante_hechos_id' => $hechos_victimizante->id,
                            'delitos_id' => $delito_id->delitos_id
                        ]);
                    }
                
                    $domicilio_notificaciones = new DomicilioNotificaciones;
                    $domicilio_notificaciones->generales_id = $general->id;
                    $domicilio_notificaciones->save();

                    $factores_vulnerabilidad = new FactoresVulnerabilidades;
                    $factores_vulnerabilidad->generales_id = $general->id;
                    $factores_vulnerabilidad->save();

                    if (strtoupper($rowH['NNA']) === 'SI') {
                        DB::table('factores_vulnerabilidades')
                        ->where('generales_id', $general->id)
                        ->update([
                            'es_adolescente' => 1
                        ]);
                    }

                    $captura = new CapturaInicial;
                    $captura->contacto_principal_telefono = $rowH['Número de contacto'];
                    $captura->contacto_principal_correo = $rowH['Correo'];
                    /*$captura->contacto_principal_nombres = $request->contacto_principal_nombres;
                    $captura->contacto_principal_primer_apellido = $request->contacto_principal_primer_apellido;
                    $captura->contacto_principal_segundo_apellido = $request->contacto_principal_segundo_apellido;
                    
                    $captura->contacto_secundario_nombres = $request->contacto_secundario_nombres;
                    $captura->contacto_secundario_primer_apellido = $request->contacto_secundario_primer_apellido;
                    $captura->contacto_secundario_segundo_apellido = $request->contacto_secundario_segundo_apellido;
                    $captura->contacto_secundario_telefono = $request->contacto_secundario_telefono;
                    $captura->contacto_secundario_correo = $request->contacto_secundario_correo;*/
                    $captura->generales_id = $general->id;
                    $captura->save();

                    $pcEntrevista = new PrimerContacto;
                    $pcEntrevista->generales_id = $general->id;
                    $pcEntrevista->registro_rev = ($rowH['Estatus de atención']=='Rev' || $rowH['Estatus de atención']=='Esperando Aprobación REV') ? 1 : null;
                    //$pcEntrevista->updated_at = ($rowH['Fecha de ingreso']!='' && $rowH['Fecha de ingreso']!=' ')?$rowH['Fecha de ingreso']:null;
                    $pcEntrevista->save();

                    $folio=explode('/', $rowH['Número de expediente']);

                    $folio_rev=explode('/', $rowH['Número de registro']);
                    
                    if(count($folio)==3){
                        if (is_numeric($folio[1])) {
                            $folios = new Folios;
                            $folios->folio = $folio[1];
                            $folios->anio = $folio[2];
                            $folios->save();
                        }
                    }

                    if(count($folio_rev)==4){
                        if (is_numeric($folio_rev[2])) {
                            $folios_rev = new FoliosRev;
                            $folios_rev->folio = $folio_rev[2];
                            $folios_rev->anio = $folio_rev[3];
                            $folios_rev->save();
                        }
                    }

                    $registro_estatal = new RegistrosEstatales;
                    $registro_estatal->fecha_atencion = ($rowH['Fecha de ingreso']!='' && $rowH['Fecha de ingreso']!=' ')?$rowH['Fecha de ingreso']:null;
                    //($this->validateDate($rowH['Fecha de ingreso']))?$rowH['Fecha de ingreso']:null;
                    $registro_estatal->folio_expediente = $num_expediente;
                    $registro_estatal->tipo_solicitantes_id = ($tipo_solicitante)? $tipo_solicitante->tipo_solicitantes_id:null;
                    $registro_estatal->generales_id = $general->id;
                    $registro_estatal->expediente_completo = 'storage/expediente_completo/'.str_replace('/', '-', $num_expediente).'.pdf';
                    $registro_estatal->save();

                }
            }
            DB::commit();
        }
        $reader->close();
        Log::info('Termino de Insertar');
    }

    private function getParentesco($parentesco)
    {
        return Parentesco::where('nombre',$parentesco)->first();
    }
    private function getDelito($delito)
    {
        return Delitos::where('nombre',$delito)->first();
    }
    private function getViolacion($violacion)
    {
        return ViolacionDH::where('nombre',$violacion)->first();
    }
    private function getSexo($sexo)
    {
        return Sexo::where('nombre',$sexo)->first();
    }
    private function getTipoSolicitante($solicitante)
    {
        return TipoSolicitante::where('nombre',$solicitante)->first();
    }
    /*private function validateDate($date)
    {
        $dia=date('d', strtotime('18/08/2022'));
        $mes=date('M', strtotime('18/08/2022'));

        if ($dia>31) 
            return false;
        
        if ($mes>12) 
            return false;
        

        return true;
    }*/
}
