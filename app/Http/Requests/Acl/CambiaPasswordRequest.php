<?php

namespace App\Http\Requests\Acl;

use App\Acl\UserACL;
use Illuminate\Foundation\Http\FormRequest;

class CambiaPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return auth()->check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clave_anterior' => UserAcl::checkUserHasPassword(request('username')) ? 'required' : '',
            'nueva_clave' => 'required|min:8|confirmed',
        ];
    }
}
