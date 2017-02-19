<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ControlesToaRequest extends FormRequest
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
            'empresa'    => 'required',
            'mes'        => 'required',
            'filtro_trx' => 'required',
            'dato'       => 'required',
        ];
    }
}
