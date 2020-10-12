<?php

namespace App\Providers;

use App\Models\Acl\App;
use App\Models\Acl\Rol;
use App\Models\Acl\Modulo;
use App\Models\Acl\Usuario;
use App\Policies\AclPolicy;
use App\Models\Gastos\Banco;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\SaldoMes;
use App\Models\Gastos\TipoGasto;
use App\Models\Gastos\TipoCuenta;
use Illuminate\Support\Facades\Gate;
use App\Models\Gastos\GlosaTipoGasto;
use App\Models\Gastos\TipoMovimiento;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',

        // ORM Acl classes
        Modulo::class => AclPolicy::class,
        Rol::class => AclPolicy::class,
        App::class => AclPolicy::class,
        Usuario::class => AclPolicy::class,

        // ORM Gastos classes
        Banco::class => AclPolicy::class,
        TipoCuenta::class => AclPolicy::class,
        Cuenta::class => AclPolicy::class,
        TipoMovimiento::class => AclPolicy::class,
        TipoGasto::class => AclPolicy::class,
        GlosaTipoGasto::class => AclPolicy::class,
        SaldoMes::class => AclPolicy::class,
        Gasto::class => AclPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
