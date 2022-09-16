<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\CambiaPasswordRequest;
use App\Models\Acl\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function showCambiaPassword(Usuario $usuario): View
    {
        return view('ACL.cambio_password', [
            'msg_alerta' => '',
            'userHasPassword' => $usuario->hasPassword(),
        ]);
    }

    public function cambiaPassword(CambiaPasswordRequest $request, Usuario $usuario): RedirectResponse
    {
        if (! $usuario->hasPassword() or $usuario->checkPassword($request->input('clave_anterior'))) {
            $usuario->storePassword($request->input('nueva_clave'));

            return redirect()->route('login');
        }

        return redirect()->back()
            ->withInput($request->input())
            ->withErrors(['clave' => 'Las credenciales son erroneas']);
    }
}
