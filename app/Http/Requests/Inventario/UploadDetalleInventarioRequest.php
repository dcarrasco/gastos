<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;

class UploadDetalleInventarioRequest extends FormRequest
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
            'id_inventario'      => 'required',
            'hoja'               => 'required|integer',
            'ubicacion'          => 'required',
            'catalogo'           => 'required',
            'descripcion'        => 'required',
            'lote'               => 'required',
            'centro'             => 'required',
            'almacen'            => 'required',
            'um'                 => 'required',
            'stock_sap'          => 'required|integer',
            'stock_fisico'       => 'required|integer',
            'digitador'          => 'required|integer',
            'auditor'            => 'required|integer',
            'fecha_modificacion' => 'required',
            'stock_ajuste'       => 'required|integer',
        ];
    }
}
