<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImprimirInventarioRequest extends FormRequest
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
            'pag_desde' => 'required|integer|min:1',
            'pag_hasta' => 'required|integer|min:'.request('pag_desde', 1),
        ];
    }
}
