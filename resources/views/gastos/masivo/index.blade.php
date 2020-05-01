@extends('layouts.app_layout')

@section('modulo')
<form method="POST" id="form-masivo">
    @csrf

    @include('gastos.masivo.index_form')

    @if (count($datosMasivos))
        <table class="table table-hover table-sm offset-md-1 col-md-10">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Glosa</th>
                    <th>Serie</th>
                    <th>Tipo Gasto</th>
                    <th class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datosMasivos as $gasto)
                    <tr>
                        <td>{{ $gasto->fecha->format('d-m-Y') }}</td>
                        <td>{{ $gasto->glosa }}</td>
                        <td>{{ $gasto->serie }}</td>
                        <td>{{ optional($gasto->tipoGasto)->tipo_gasto }} </td>
                        <td class="text-right">
                            {{ fmtMonto($gasto->monto) }}
                            <x-signo-movimiento :movimiento=$gasto />
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <th>Total {{ $datosMasivos->count() }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-right">{{ fmtMonto($datosMasivos->pluck('monto')->sum()) }}</th>
                </tr>
            </tbody>
        </table>

        @if ($agregarDatosMasivos)
            <div class="form-row">
                <div class="offset-md-1 col-md-10 text-right">
                    <button name="agregar" value="agregar" class="btn btn-secondary">Agregar</button>
                </div>
            </div>
        @endif
    @endif
</form>

<script type="text/javascript">
    $('button[name="agregar"]').click(function(e) {
        e.preventDefault();
        $('#form-masivo').attr('action', '{{ route("gastos.ingresoMasivoAdd") }}');
        $('#form-masivo').submit();
    });
</script>
@endsection
