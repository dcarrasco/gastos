@extends('common.app_layout')

@section('modulo')
{{ Form::open() }}
    <div class="form-row">
        <div class="offset-md-3 col-md-2">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Año</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Mes</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-3 col-md-2">
            {{ Form::select('cuenta_id', $formCuenta, request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('anno', $formAnno, request('anno', $annoDefault), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('mes', $formMes, request('mes', $mesDefault), ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-2 col-md-2">
            <label class="col-form-label">Datos</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-2 col-md-8"> {{ Form::textarea('datos', request('datos'), ['class' => 'form-control']) }} </div>
    </div>

    <div class="form-row">
        <div class="offset-md-8 col-md-2 text-right">
            <button type="submit" class="btn btn-primary">Procesar</button>
        </div>
    </div>


@if (count($datosMasivos))
<table class="table table-hover table-sm offset-md-2 col-md-8">
    @foreach($datosMasivos as $gasto)
        @if ($loop->first)
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
        @endif
        <tr>
            <td> {{ $gasto->fecha }} </td>
            <td> {{ $gasto->glosa }} </td>
            <td> {{ $gasto->serie }} </td>
            <td> {{ optional($gasto->tipoGasto)->tipo_gasto }} </td>
            <td class="text-right">
                $&nbsp;{{ number_format($gasto->monto, 0, ",", ".") }}
                @if (optional($gasto->tipoMovimiento)->signo == -1)
                    <small><span class="fa fa-minus-circle text-danger"></span></small>
                @else
                    <small><span class="fa fa-plus-circle text-success"></span></small>
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <th>Total {{ $datosMasivos->count() }}</th>
        <th></th>
        <th></th>
        <th></th>
        <th class="text-right">{{ number_format($datosMasivos->pluck('monto')->sum(),0, ",", ".") }}</th>
    </tr>
    </tbody>
</table>

<div class="form-row">
    <div class="offset-md-8 col-md-2 text-right">
        <button name="agregar" value="agregar" class="btn btn-secondary">Agregar</button>
    </div>
</div>

{{ Form::close() }}
@endif

@endsection
