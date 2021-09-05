<?php

namespace App\Http\Requests\Gasto;

use App\Models\Gastos\Gasto;
use Illuminate\Foundation\Http\FormRequest;

class DeleteGastoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('delete', $this->route('gasto'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
