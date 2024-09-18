<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\NumeroPaso;


class PrimerContactoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        $numeroPaso = $this->numeroPaso;
        switch ($numeroPaso) {
            case NumeroPaso::PASO1:
                return [
                    'institucion' => 'max:150',
                    'no_oficio' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO2:
                return [
                    'solicitante_nombres' => 'required|max:150',
                    'solicitante_primer_apellido' => 'required|max:150',
                    'solicitante_segundo_apellido' => 'required|max:150',
                    'fecha_nacimiento' => 'required',
                    'poblacion' => 'max:150',
                    'telefonos.*' => 'nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
                ];
                break;
            case NumeroPaso::PASO3:
                return [
                    'hechos_relato' => 'sometimes|nullable|min:30|max:1000',
                    'hechos_poblacion' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO4:
                return [
                    'orientacion_brindada' => 'max:250',
                ];
                break;
            case NumeroPaso::PASO5:
                return [
                    'victima_nombres' => 'max:150',
                    'victima_primer_apellido' => 'max:150',
                    'victima_segundo_apellido' => 'max:150',
                    'victima_fecha_nacimiento' => 'required',
                    
                    'no_carpeta' => 'max:150',
                    'fiscalia' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO6:
                return [
                    'familiar_observaciones' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO7:
                return [
                    'calle' => 'max:150',
                    'no_exterior' => 'max:150',
                    'no_interior' => 'max:150',
                    'colonia' => 'max:150',
                    'codigo_postal' => 'max:150',
                    'localidad' => 'max:150',
                    'estado_civil' => 'max:150',
                    'escolaridad' => 'max:150',
                    'cual_organizacion_pertenece' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO8:
                return [
                    'cual_actividad_ingreso' => 'max:150',
                    'cual_programa_social' => 'max:150',
                    'cual_apoyo_federacion_estado' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO9:
                return [
                    'cual_afectacion_salud' => 'max:150',
                    'cual_servicio_atencion_medica' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO10:
                return [
                    'vulnerabilidades_observaciones' => 'max:150',
                ];
                break;
            case NumeroPaso::PASO11:
                return [
                    'gestion_medica_necesidades' => 'max:150',
                    'gestion_medica_derivacion' => 'max:150', 
                    'gestion_social_necesidades' => 'max:150', 
                    'gestion_social_derivacion' => 'max:150',
                    'gestion_social_num_seguro_social' => 'max:150', 
                    'atencion_psicologica_necesidades' => 'max:150', 
                    'atencion_psicologica_derivacion' => 'max:150',
                    'asesoria_juridica_necesidades' => 'max:150', 
                    'asesoria_juridica_derivacion' => 'max:150', 
                    'asesoria_juridica_opciones_otros' => 'max:150'
                ];
                break;
            case NumeroPaso::PASO12:
                return [
                    'quien_ejerce_custodia_rev' => 'max:150', 
                    'observaciones_rev' => 'max:150', 
                    'como_se_entero_ceeaiv' => 'max:150', 
                ];
                break;
            default:
                return [
                    'institucion' => 'max:150',
                    'no_oficio' => 'max:150',
                    'solicitante_nombres' => 'required|max:150',
                    'solicitante_primer_apellido' => 'required|max:150',
                    'solicitante_segundo_apellido' => 'required|max:150',
                    'poblacion' => 'max:150',
                    'telefonos.*' => 'nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
                    'hechos_relato' => 'sometimes|nullable|min:30|max:1000',
                    'hechos_poblacion' => 'max:150',
                    'orientacion_brindada' => 'max:250',
                    'victima_nombres' => 'max:150',
                    'victima_primer_apellido' => 'max:150',
                    'victima_segundo_apellido' => 'max:150',
                    'no_carpeta' => 'max:150',
                    'fiscalia' => 'max:150',
                    'familiar_observaciones' => 'max:150',
                    'calle' => 'max:150',
                    'no_exterior' => 'max:150',
                    'no_interior' => 'max:150',
                    'colonia' => 'max:150',
                    'codigo_postal' => 'max:150',
                    'localidad' => 'max:150',
                    'estado_civil' => 'max:150',
                    'escolaridad' => 'max:150',
                    'cual_organizacion_pertenece' => 'max:150',
                    'cual_actividad_ingreso' => 'max:150',
                    'cual_programa_social' => 'max:150',
                    'cual_apoyo_federacion_estado' => 'max:150',
                    'cual_afectacion_salud' => 'max:150',
                    'cual_servicio_atencion_medica' => 'max:150',
                    'vulnerabilidades_observaciones' => 'max:150',
                    'gestion_medica_necesidades' => 'max:150',
                    'gestion_medica_derivacion' => 'max:150', 
                    'gestion_social_necesidades' => 'max:150', 
                    'gestion_social_derivacion' => 'max:150',
                    'gestion_social_num_seguro_social' => 'max:150', 
                    'atencion_psicologica_necesidades' => 'max:150', 
                    'atencion_psicologica_derivacion' => 'max:150',
                    'asesoria_juridica_necesidades' => 'max:150', 
                    'asesoria_juridica_derivacion' => 'max:150', 
                    'asesoria_juridica_opciones_otros' => 'max:150',
                    'quien_ejerce_custodia_rev' => 'max:150', 
                    'observaciones_rev' => 'max:150', 
                    'como_se_entero_ceeaiv' => 'max:150',
                    //'fecha_nacimiento' => 'required',
                    //'victima_fecha_nacimiento' => 'required',
                ];
                break;
        }
        return [];
    }
}
