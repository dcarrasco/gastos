<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('invfija.app_nombre') }}</title>

    <link rel="icon" href="{{ asset('img/favicon-512.png') }}" type="image/png" />
    <link rel="apple-touch-icon-precomposed" sizes="512x512" href="{{ asset('img/favicon-512.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/fix_bootstrap.css') }}" />

    <script type="text/javascript">
        var js_base_url = "{{ asset('') }}";
    </script>

    @if (auth()->guest())
        <style type="text/css">body {margin-top: 40px; background-image: url("{{ asset('img/tch-background.jpg') }}"); background-size: cover;}</style>
    @endif
</head>