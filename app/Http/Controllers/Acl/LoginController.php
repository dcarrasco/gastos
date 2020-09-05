<?php

namespace App\Http\Controllers\Acl;

use App\Models\Acl\UserACL;
use App\Models\Acl\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\CambiaPasswordRequest;

class LoginController extends Controller
{
    public function showCambiaPassword(Usuario $usuario)
    {
        return view('acl.cambio_password', [
            'msg_alerta' => '',
            'userHasPassword' => $usuario->hasPassword(),
        ]);
    }

    public function cambiaPassword(CambiaPasswordRequest $request, Usuario $usuario)
    {
        if (!$usuario->hasPassword() or $usuario->checkPassword($request->clave_anterior)) {
            $usuario->storePassword($request->nueva_clave);

            return redirect()->route('login');
        }

        return redirect()->back()
            ->withInput($request->input())
            ->withErrors(['clave' => 'Las credenciales son erroneas']);
    }
}
