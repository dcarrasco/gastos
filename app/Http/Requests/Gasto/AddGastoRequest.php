<?php

namespace App\Http\Requests\Gasto;

use Illuminate\Foundation\Http\FormRequest;

class AddGastoRequest extends FormRequest
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
            'cuenta_id' => 'required',
            'anno' => 'required',
            'mes' => 'required',
            'fecha' => 'required',
            'glosa' => 'required',
            'serie' => 'required',
            'tipo_gasto_id' => 'required',
            'monto' => 'required|numeric',
        ];
    }
}
