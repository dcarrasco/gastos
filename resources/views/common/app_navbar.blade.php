<!-- ============================== NAVBAR ============================== -->
<nav class="navbar navbar-expand-lg p-0 navbar-light shadow-sm" style="background-color: #fff;" role="navigation">
    <div class="h-100 col-2 d-inline-block bg-dark py-2 text-center">
        <span class="navbar-brand text-light">
            {{ config('app.name') }}
        </span>
    </div>

    <button class="navbar-toggler" data-toggle="collapse" data-target="#navMenuCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse col-10" id="navMenuCollapse">

        <span class="h5 my-0 mx-2">
            {!! auth()->user()->moduloAppName() !!}
        </span>

        @if (isset($menuModulo))
        <ul class="navbar-nav">
            <li class="nav-item dropdown active mx-3">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fa fa-{{ $menuModulo->pluck('icono', 'resource')->get($moduloSelected) }} fa-fw"></span>
                    {{ $menuModulo->pluck('nombre', 'resource')->get($moduloSelected) }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    @foreach ($menuModulo as $modulo )
                    <a href="{{ $modulo['url'] }}" class="dropdown-item @if($modulo['resource'] == $moduloSelected) active @endif">
                        <span class="fa fa-{{ $modulo['icono'] }} fa-fw"></span>
                        {!! $modulo['nombre'] !!}
                    </a>
                    @endforeach
                </div>
            </li>
        </ul>
        @endif

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <span class="fa fa-power-off fa-fw"></span>
                    Logout {{ auth()->user()->getFirstName() }}
                    <img src="https://secure.gravatar.com/avatar/{{ md5(auth()->user()->email) }}?size=24" class="rounded-circle border mx-2" />
                </a>
                {{ Form::open(['url' => route('logout'), 'id' => 'logout-form']) }}{{ Form::close() }}
            </li>
        </ul>

    </div> <!-- DIV class="collapse navbar-collapse navMenuCollapse" -->
</nav>
<!-- ============================== /NAVBAR ============================== -->
