<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubirEscanerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'file' => 'mimes:pdf',
            'file' => 'max:2000'
        ];
    }
}