<?php

namespace App\Providers;

use App\Policies\AclPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

        // ORM Acl classes
        \App\Models\Acl\Modulo::class => AclPolicy::class,
        \App\Models\Acl\Rol::class => AclPolicy::class,
        \App\Models\Acl\App::class => AclPolicy::class,
        \App\Models\Acl\Usuario::class => AclPolicy::class,

        // ORM Gastos classes
        \App\Models\Gastos\Banco::class => AclPolicy::class,
        \App\Models\Gastos\TipoCuenta::class => AclPolicy::class,
        \App\Models\Gastos\Cuenta::class => AclPolicy::class,
        \App\Models\Gastos\TipoMovimiento::class => AclPolicy::class,
        \App\Models\Gastos\TipoGasto::class => AclPolicy::class,
        \App\Models\Gastos\GlosaTipoGasto::class => AclPolicy::class,
        \App\Models\Gastos\SaldoMes::class => AclPolicy::class,
        \App\Models\Gastos\Gasto::class => AclPolicy::class,
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
