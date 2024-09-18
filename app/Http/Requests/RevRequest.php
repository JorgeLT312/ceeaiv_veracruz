<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\NumeroPaso;


class RevRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'tipo_victimas_id' => 'required|exists:tipo_victimas,tipo_victimas_id',
            'victima_nombres' => 'required|max:150',
            'victima_primer_apellido' => 'required|max:150',
            'victima_segundo_apellido' => 'required|max:150',
            'victima_curp' => 'max:150',
            'victima_poblacion' => 'max:150',
        ];
    }
}
