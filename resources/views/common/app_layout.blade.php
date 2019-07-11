<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('invfija.app_nombre') }}</title>

    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ asset('img/favicon-152.png') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/fix_bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.jqplot.min.css') }}" />

    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/locales/bootstrap-datepicker.es.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.jqplot.min.js') }}"></script>

    <script type="text/javascript">
        var js_base_url = "{{ asset('') }}";
    </script>

    @if (auth()->guest())
    <style type="text/css">body {margin-top: 40px; background-image: url("{{ asset('img/tch-background.jpg') }}"); background-size: cover;}</style>
    @endif
</head>

<body>
    @includeWhen(auth()->check(), 'common.app_navbar')

    <div class="container-fluid" id="container">
        <div class="row mh-100" style="min-height: 100vh;">

            <div class="col-2 bg-secondary px-0">
                @include('common.app_menu_modulo')
            </div>

            <div class="col-10">
                @include('common.alert')
                @yield('modulo')
                @include('common.app_footer')
            </div>

        </div> <!-- DIV   class="row"    -->
    </div> <!-- DIV principal de la aplicacion   class="container"-->

</body>
</html>
