<?php

namespace App\Http\Requests\Toa;

use Illuminate\Foundation\Http\FormRequest;

class ConsumosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reporte'     => 'required',
            'fecha_desde' => 'required',
            'fecha_hasta' => 'required',
        ];
    }
}
