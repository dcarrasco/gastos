@extends('common.app_layout')

@section('modulo')
<form>
    <div class="form-row">
        <div class="offset-md-3 col-md-3">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Año</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-3 col-md-3">
            {{ Form::select('cuenta_id', $cuentas, request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::selectYear('anno', $today->year, 2015, request('anno', $today->year), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>


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
            $&nbsp;{{ number_format($mov->monto, 0, ',', '.') }}
            @if ($mov->tipoMovimiento->signo == -1)
                {{-- <small><span class="fa fa-minus-circle text-danger"></span></small> --}}
                <svg height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg" style="fill: #e3342f"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm4-8a1 1 0 0 1-1 1H9a1 1 0 0 1 0-2h6a1 1 0 0 1 1 1z"/></svg>
            @else
                {{-- <small><span class="fa fa-plus-circle text-success"></span></small> --}}
                <svg height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg" class="align-middle" style="fill: #32c172"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-9h2a1 1 0 0 1 0 2h-2v2a1 1 0 0 1-2 0v-2H9a1 1 0 0 1 0-2h2V9a1 1 0 0 1 2 0v2z"/></svg>
            @endif
        </td>
        <td class="text-right">
            $&nbsp;{{ number_format($saldo += $mov->valor_monto, 0, ',', '.') }}
        </td>
        <td>
            {{ Form::open(['url' => route('gastos.borrarGasto', http_build_query(request()->all()))]) }}
                {!! method_field('DELETE')!!}
                {{ Form::hidden('id', $mov->getKey()) }}
                <button type="submit" class="btn btn-sm btn-link p-0 align-top">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                </button>
            {{ Form::close() }}
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
                <th class="text-center">{{ optional(optional($inversion->saldoFinal())->tipoMovimiento)->tipo_movimiento }}</th>
                <th></th>
                <th class="text-right">
                    $&nbsp;{{ number_format(optional($inversion->saldoFinal())->monto, 0, ',', '.') }}
                </th>
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
                    $&nbsp;{{ number_format($inversion->util($inversion->saldoFinal()), 0, ',', '.') }}
                </th>
                <th></th>
                <th class="text-right">
                    {{ number_format(100*$inversion->rentabilidad($inversion->saldoFinal()), 2, ',', '.') }}%
                </th>
                <th class="text-right">
                    {{ number_format(100*$inversion->rentabilidadAnual($inversion->saldoFinal()), 2, ',', '.') }}%
                </th>
            </tr>
        @endif
    @endif
@endforeach

{{ Form::open([]) }}
<tr>
    {{ Form::hidden('cuenta_id', request('cuenta_id', $cuentas->keys()->first())) }}
    {{ Form::hidden('anno', request('anno', $today->year)) }}
    <td></td>
    <td></td>
    <td>
        <input type="date" name="fecha" value="{{ old('fecha') }}" autocomplete="off" class="form-control form-control-sm @error('fecha') is-invalid @enderror">
    </td>
    <td>
        <input type="text" name="glosa" value="{{ old('glosa') }}" autocomplete="off" class="form-control form-control-sm @error('glosa') is-invalid @enderror">
    </td>
    <td>
        {{ Form::select('tipo_movimiento_id', $tiposMovimientos, request('tipo_movimiento_id'), ['class' => 'form-control form-control-sm']) }}
    </td>
    <td>
        <input type="text" name="monto" value="{{ old('monto') }}" autocomplete="off" class="form-control form-control-sm @error('monto') is-invalid @enderror">
    </td>
    <td>
        <button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button>
    </td>
    <td></td>
    <td></td>
    <td></td>
</tr>
{{ Form::close() }}
</tbody>
</table>


@if(! empty($datosInversion = $inversion->getJSONRentabilidadesAnual()))
    <div id="chartInversion" class="col-md-10 offset-md-1 my-5 bg-white border rounded" style="height: 400px">
        <canvas id="canvas-chartInversion"></canvas>
    </div>

    <script type="text/javascript" src="{{ asset('js/Chart.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/Chart.bundle.min.js') }}"></script>

    <script type="text/javascript">

    var chartInversion;
    var datosInversion = {!! $datosInversion !!}

    var chartDataInversion = {
        labels: datosInversion.label,
        datasets: [{
            label: 'Rentabilidad (%)',
            fill: true,
            backgroundColor: 'rgba(54,162,235,0.1)',
            borderColor: 'rgb(54,162,235)',
            pointBackgroundColor: 'rgb(54,162,235)',
            yAxisID: 'y-axis-1',
            data: datosInversion.rentabilidad
        }, {
            label: 'Saldo ($)',
            fill: true,
            backgroundColor: 'rgba(249,143,54,0.0)',
            borderColor: 'rgb(249,143,54)',
            pointBackgroundColor: 'rgb(249,143,54)',
            yAxisID: 'y-axis-2',
            data: datosInversion.saldo
        }]
    };

    var optionsInversion = {
        backgroundColor: 'rgba(0,0,0,1)',
        borderColor: 'rgba(0,0,0,1)',
        title: {
            display: true,
            text: 'Desempeño Inversion'
        },
        legend: {
            display: true,
            position: "bottom",
            labels: {
                boxWidth: 20,
                padding: 40
            }
        },
        elements: {
            line: {
                tension: 0,
            }
        },
        tooltips: {
            callbacks: {
                label: function(toolTipItem, data) {
                    if (toolTipItem.datasetIndex == 0) {
                        return 'Rentabilidad ' + Math.round(toolTipItem.yLabel*10000) / 100 + '%';
                    } else {
                        return 'Saldo $ ' + toolTipItem.yLabel.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                    }
                }
            }
        },
        scales: {
            xAxes: [{
                display: true,
                ticks: {
                    fontSize: 10
                },
            }],
            yAxes: [{
                id: 'y-axis-1',
                type: 'linear',
                display: true,
                position: 'left',
                scaleLabel: {
                    display: true,
                    labelString: 'Rentabilidad'
                },
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                        return Math.round(value*100) + '%';
                    }
                }
            }, {
                id: 'y-axis-2',
                type: 'linear',
                display: true,
                position: 'right',
                scaleLabel: {
                    display: true,
                    labelString: 'Saldo'
                },
                gridLines: {
                    display: false
                },
                ticks: {
                    callback: function(value, index, values) {
                        return 'MM$ ' + (Math.round(value/100000)/10).toString().replace(/\./, ',');
                    }
                }
            }]
        }
    };

    function drawCardChartInversion() {
        var ctx = document.getElementById('canvas-chartInversion').getContext('2d');

        chartInversion = new Chart(ctx, {
            type: 'line',
            data: chartDataInversion,
            options: optionsInversion
        });
    }

    $(document).ready(function() {
        drawCardChartInversion();
    });

    </script>
@endif


@endsection
