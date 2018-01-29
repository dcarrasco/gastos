<?php

namespace App\Http\Controllers\Acl;

use App\Acl\UserACL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\CambiaPasswordRequest;

class LoginController extends Controller
{

    public function showCambiaPassword()
    {
        return view('acl.cambio_password', [
            'msg_alerta' => '',
            'userHasPassword' => UserAcl::checkUserHasPassword(request('username')),
        ]);
    }

    public function cambiaPassword(CambiaPasswordRequest $request)
   {
        if (!UserAcl::checkUserHasPassword(request('username')) or UserACL::checkUserPassword(request('username'), request('clave_anterior'))) {
            UserACL::storeUserPassword(request('username'), request('nueva_clave'));
            return redirect()->route('login');
        }

        return redirect()->back()
            ->withInput(request()->input())
            ->withErrors(['clave'=>'Las credenciales son erroneas']);
   }
}
