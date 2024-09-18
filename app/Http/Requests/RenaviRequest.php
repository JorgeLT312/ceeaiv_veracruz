<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\NumeroPaso;

class RenaviRequest extends FormRequest
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
                    'victima_nombres' => 'required|max:150',
                    'victima_primer_apellido' => 'required|max:150',
                    'victima_segundo_apellido' => 'required|max:150',
                ];
                break;
            case NumeroPaso::PASO4:
                return [
                    'victima_nombres' => 'required|max:150',
                    'victima_primer_apellido' => 'required|max:150',
                    'victima_segundo_apellido' => 'required|max:150',
                ];
                break;
            default:
                return [
                    'victima_nombres' => 'required|max:150',
                    'victima_primer_apellido' => 'required|max:150',
                    'victima_segundo_apellido' => 'required|max:150',
                ];
                break;
        }
        return [
            'file' => 'mimes:pdf',
            'file' => 'max:2000'
        ];
    }
}