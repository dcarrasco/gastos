<?php

namespace App\Http\Requests\Gasto;

use App\Models\Gastos\Gasto;
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
        return auth()->user()->can('create', Gasto::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'cuenta_id' => 'required',
            'anno' => 'required|numeric',
            'mes' => 'required|numeric',
            'fecha' => 'required|date',
            'glosa' => 'required',
            'serie' => 'required',
            'tipo_gasto_id' => 'required',
            'monto' => 'required|numeric',
        ];
    }
}
