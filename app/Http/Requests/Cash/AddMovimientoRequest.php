<?php

namespace App\Http\Requests\Cash;

use App\Models\Cash\Movimiento;
use Illuminate\Foundation\Http\FormRequest;

class AddMovimientoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', Movimiento::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            /* 'movimiento_id' => 'required', */
            'cuenta_id' => 'required|numeric',
            'fecha' => 'required|date',
            'descripcion' => 'required',
            'contracuenta_id' => 'required|numeric',
            /* 'conciliado' => 'required', */
            'tipo_cargo' => 'required',
            'monto' => 'required|numeric',
            /* 'balance' => 'required|numeric', */
        ];
    }
}
