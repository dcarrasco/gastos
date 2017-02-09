<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Inventario;

class AjusteInventarioRequest extends FormRequest
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
        return array_collapse(Inventario::getInventarioActivo()->detalleAjustes()
            ->map(function ($elem) {
                return ['stock_ajuste_'.$elem->id => 'required|integer|min:0'];
            })
            ->all()
        );
    }
}
