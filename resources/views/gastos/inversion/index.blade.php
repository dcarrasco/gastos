@extends('layouts.app_layout')

@section('modulo')

@include('gastos.inversion.index_form')

<table class="col-md-12 mt-md-3 table table-hover table-sm">
    <thead class="thead-light">
        <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Fecha</th>
            <th>Glosa</th>
            <th>Tipo Movimiento</th>
            <th class="text-right">Monto</th>
            <th class="text-right">Saldo</th>
            <th></th>
            <th class="text-right">Rentab</th>
            <th class="text-right">Rentab Año</th>
        </tr>
    </thead>

    <tbody>
        <?php $saldo = 0; ?>
        @foreach ($inversion->getMovimientos() as $mov)
            <tr>
                <td>{{ $mov->anno }}</td>
                <td>{{ $mov->mes }}</td>
                <td>{{ optional($mov->fecha)->format('d-m-Y') }}</td>
                <td>{{ $mov->glosa }}</td>
                <td class="text-center">{{ $mov->tipoMovimiento->tipo_movimiento }}</td>
                <td class="text-right">
                    {{ fmtMonto($mov->monto) }}
                    <x-signo-movimiento :movimiento=$mov />
                </td>
                <td class="text-right">{{ fmtMonto($saldo += $mov->valor_monto) }}</td>
                <td>
                    <form method="POST" action="{{ route('gastos.borrarGasto', http_build_query(request()->all())) }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $mov->getKey() }}">
                        <button type="submit" class="btn btn-sm btn-link p-0 align-top">
                            <x-heroicon.delete width="14" height="14"/>
                        </button>
                    </form>
                </td>
                <td></td>
                <td></td>
            </tr>

            @if ($loop->last)
                @if($inversion->saldoFinal())
                    <tr class="bg-light">
                        <th>{{ optional($inversion->saldoFinal())->anno }}</th>
                        <th>{{ optional($inversion->saldoFinal())->mes }}</th>
                        <th>{{ optional(optional($inversion->saldoFinal())->fecha)->format('d-m-Y') }}</th>
                        <th>{{ optional($inversion->saldoFinal())->glosa }}</th>
                        <th class="text-center">
                            {{ optional(optional($inversion->saldoFinal())->tipoMovimiento)->tipo_movimiento }}
                        </th>
                        <th></th>
                        <th class="text-right">{{ fmtMonto(optional($inversion->saldoFinal())->monto) }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr class="bg-light">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-right">Utilidad</th>
                        <th class="text-right">
                            {{ fmtMonto($inversion->util($inversion->saldoFinal())) }}
                        </th>
                        <th></th>
                        <th class="text-right">
                            {{ fmtPorcentaje(100*$inversion->rentabilidad($inversion->saldoFinal())) }}
                        </th>
                        <th class="text-right">
                            {{ fmtPorcentaje(100*$inversion->rentabilidadAnual($inversion->saldoFinal())) }}
                        </th>
                    </tr>
                @endif
            @endif
        @endforeach

        <tr>
            <form method="POST">
                @csrf
                <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $cuentas->keys()->first()) }}">
                <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
                <td></td>
                <td></td>
                <td><x-form-input name="fecha" type="date" class="form-control-sm" /></td>
                <td><x-form-input name="glosa" class="form-control-sm" /></td>
                <td><x-form-input name="tipo_movimiento_id" type="select" class="custom-select-sm" :options=$tiposMovimientos /></td>
                <td><x-form-input name="monto" class="form-control-sm" /></td>
                <td>
                    <button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </form>
        </tr>
    </tbody>
</table>

@includeWhen(! empty($datosInversion = $inversion->getJSONRentabilidadesAnual()), 'gastos.inversion.index_chart')

@endsection
