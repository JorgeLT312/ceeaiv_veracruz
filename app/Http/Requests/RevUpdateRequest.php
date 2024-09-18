<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\NumeroPaso;
use App\Enums\NumeroSubPaso;


class RevUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        switch ($this->numeroPaso) {
            case NumeroPaso::PASO1:
                return [
                    'lugar' => 'required|max:150',
                    'fecha_atencion' => 'required|max:150',
                    'solicitante_nombres' => 'sometimes|nullable|max:150',
                    'solicitante_primer_apellido' => 'sometimes|nullable|max:150',
                    'solicitante_segundo_apellido' => 'sometimes|nullable|max:150',
                    'cargo' => 'sometimes|nullable|max:150',
                    'dependencia' => 'sometimes|nullable|max:150',
                    'solicitante_nombres' => 'required_if:tipo_solicitantes_id,in:2,4',
                    'solicitante_primer_apellido' => 'required_if:tipo_solicitantes_id,in:2,4',
                    'solicitante_segundo_apellido' => 'required_if:tipo_solicitantes_id,in:2,4',
                ];
                break;
            case NumeroPaso::PASO2:
                switch ($this->numeroSubPaso) {
                    case NumeroSubPaso::SUBPASO1:
                        return [
                            'tipo_victimas_id' => 'required|exists:tipo_victimas,tipo_victimas_id',
                            'victima_nombres' => 'required|max:150',
                            'victima_primer_apellido' => 'required|max:150',
                            'victima_segundo_apellido' => 'required|max:150',
                            'victima_curp' => 'sometimes|nullable|max:150',
                            'victima_poblacion' => 'sometimes|nullable|max:150',
                            'victima_fecha_nacimiento' => 'required',
                        ];
                        break;
                    case NumeroSubPaso::SUBPASO2:
                        return [
                            'calle' => 'sometimes|nullable|max:150',
                            'no_exterior' => 'sometimes|nullable|max:150',
                            'no_interior' => 'sometimes|nullable|max:150',
                            'colonia' => 'sometimes|nullable|max:150',
                            'codigo_postal' => 'sometimes|nullable|max:150',
                            'localidad' => 'sometimes|nullable|max:150',
                            'telefono' => 'sometimes|nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
                            'solicitante_territorios_id' => 'required'
                        ];
                        break;
                }
                break;
            case NumeroPaso::PASO3:
                return [
                    'victima_directa_nombres' => 'required|max:150',
                    'victima_directa_primer_apellido' => 'required|max:150',
                    'victima_directa_segundo_apellido' => 'required|max:150',
                ];
                break;
            case NumeroPaso::PASO4:
                return [
                    'hechos_relato' => 'sometimes|nullable|min:250|max:1000',
                    'conoce_hechos_relato' => 'sometimes|nullable|max:150',
                    'hechos_calle' => 'sometimes|nullable|max:150',
                    'hechos_no_exterior' => 'sometimes|nullable|max:150',
                    'hechos_no_interior' => 'sometimes|nullable|max:150',
                    'hechos_colonia' => 'sometimes|nullable|max:150',
                    'hechos_codigo_postal' => 'sometimes|nullable|max:150',
                    'hechos_localidad' => 'sometimes|nullable|max:150',
                ];
                break;
            case NumeroPaso::PASO5:
                return [
                    'ministerio_public_agencia' => 'sometimes|nullable|max:150',
                    'ministerio_public_apciac' => 'sometimes|nullable|max:150',
                    'ministerio_public_estado_investigacion' => 'sometimes|nullable|max:150',
                    'autoridad_observaciones' => 'sometimes|nullable|max:150',
                    'proceso_judicial_numero_juzgado' => 'sometimes|nullable|max:150',
                    'proceso_judicial_numero_proceso' => 'sometimes|nullable|max:150',
                    'proceso_judicial_estado_proceso' => 'sometimes|nullable|max:150',
                    'ddhh_folio' => 'sometimes|nullable|max:150',
                    'ddhh_organismo' => 'sometimes|nullable|max:150',
                    'ddhh_estado_actual' => 'sometimes|nullable|max:150',
                    'otra_nombre_autoridad' => 'sometimes|nullable|max:150',
                    'ddhh_autoridad_responsable' => 'sometimes|nullable|max:150',
                ];
                break;
            default:
                return [
                    'lugar' => 'sometimes|nullable|max:150',
                    'fecha_atencion' => 'sometimes|nullable|max:150',
                    'solicitante_nombres' => 'sometimes|nullable|max:150',
                    'solicitante_primer_apellido' => 'sometimes|nullable|max:150',
                    'solicitante_segundo_apellido' => 'sometimes|nullable|max:150',
                    'cargo' => 'sometimes|nullable|max:150',
                    'dependencia' => 'sometimes|nullable|max:150',
                    'solicitante_nombres' => 'required_if:tipo_solicitantes_id,in:2,4',
                    'solicitante_primer_apellido' => 'required_if:tipo_solicitantes_id,in:2,4',
                    'solicitante_segundo_apellido' => 'required_if:tipo_solicitantes_id,in:2,4',

                    //'tipo_victimas_id' => 'exists:tipo_victimas,tipo_victimas_id',
                    'victima_nombres' => 'sometimes|nullable|max:150',
                    'victima_primer_apellido' => 'sometimes|nullable|max:150',
                    'victima_segundo_apellido' => 'sometimes|nullable|max:150',
                    'victima_curp' => 'sometimes|nullable|max:150',
                    'victima_poblacion' => 'sometimes|nullable|max:150',

                    'calle' => 'sometimes|nullable|max:150',
                    'no_exterior' => 'sometimes|nullable|max:150',
                    'no_interior' => 'sometimes|nullable|max:150',
                    'colonia' => 'sometimes|nullable|max:150',
                    'codigo_postal' => 'sometimes|nullable|max:150',
                    'localidad' => 'sometimes|nullable|max:150',
                    'telefono' => 'sometimes|nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',

                    'victima_directa_nombres' => 'sometimes|nullable|max:150',
                    'victima_directa_primer_apellido' => 'sometimes|nullable|max:150',
                    'victima_directa_segundo_apellido' => 'sometimes|nullable|max:150',

                    'hechos_relato' => 'sometimes|nullable|max:1000',
                    'conoce_hechos_relato' => 'sometimes|nullable|max:150',
                    'hechos_calle' => 'sometimes|nullable|max:150',
                    'hechos_no_exterior' => 'sometimes|nullable|max:150',
                    'hechos_no_interior' => 'sometimes|nullable|max:150',
                    'hechos_colonia' => 'sometimes|nullable|max:150',
                    'hechos_codigo_postal' => 'sometimes|nullable|max:150',
                    'hechos_localidad' => 'sometimes|nullable|max:150',

                    'ministerio_public_agencia' => 'sometimes|nullable|max:150',
                    'ministerio_public_apciac' => 'sometimes|nullable|max:150',
                    'ministerio_public_estado_investigacion' => 'sometimes|nullable|max:150',
                    'autoridad_observaciones' => 'sometimes|nullable|max:150',
                    'proceso_judicial_numero_juzgado' => 'sometimes|nullable|max:150',
                    'proceso_judicial_numero_proceso' => 'sometimes|nullable|max:150',
                    'proceso_judicial_estado_proceso' => 'sometimes|nullable|max:150',
                    'ddhh_folio' => 'sometimes|nullable|max:150',
                    'ddhh_organismo' => 'sometimes|nullable|max:150',
                    'ddhh_estado_actual' => 'sometimes|nullable|max:150',
                    'otra_nombre_autoridad' => 'sometimes|nullable|max:150',
                    'ddhh_autoridad_responsable' => 'sometimes|nullable|max:150',

                    'tutor_nombres' => 'sometimes|nullable|max:150',
                    'tutor_primer_apellido' => 'sometimes|nullable|max:150',
                    'tutor_segundo_apellido' => 'sometimes|nullable|max:150',
                    'tutor_contacto' => 'sometimes|nullable|max:150',
                    'poblacion_indigena' => 'sometimes|nullable|max:150',
                    'tramite_condicion' => 'sometimes|nullable|max:150',
                    'pertenece_institucion_otra' => 'sometimes|nullable|max:150',
                    'tipo_medio_informativo' => 'sometimes|nullable|max:150',
                    'nombre_medio_informativo' => 'sometimes|nullable|max:150',
                ];
                break;
        }
    }
}
