<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;

class EditarRequest extends FormRequest
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
            'ubicacion'    => 'required',
            'catalogo'     => 'required',
            'lote'         => 'required',
            'um'           => 'required',
            'centro'       => 'required',
            'almacen'      => 'required',
            'stock_fisico' => 'required|integer|min:0',
            'hu'           => '',
            'observacion'  => '',
        ];
    }
}
