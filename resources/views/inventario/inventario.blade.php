@extends('common.app_layout')

@section('modulo')
<div class="col-md-12 well">
    {{ Form::open(['method' => 'GET', 'id' => 'frm_buscar', 'role' => 'form', 'class' => 'form-inline']) }}
    <div class="form-group col-md-4">
        <label>{{ trans('inventario.report_label_inventario') }}</label>
        <p class="form-control-static">{{ $inventario }}</p>
    </div>

    <div class="form-group col-md-3">
        <label>{{ trans('inventario.page') }}</label>
        <div class="input-group col-md-7">
            <div class="input-group">
                <span class="input-group-btn">
                    <a href="#" class="btn btn-default btn-sm" id="btn_buscar">
                        <span class="fa fa-search"></span>
                    </a>
                </span>
                {{ Form::text('hoja', $hoja, ['maxlength' => 10, 'id' => 'id_hoja', 'class' => 'form-control input-sm']) }}
                <span class="input-group-btn">
                    <a href="{{ $linkHojaAnt }}" class="btn btn-default btn-sm" id="btn_hoja_ant">
                        <span class="fa fa-chevron-left"></span>
                    </a>
                    <a href="{{ $linkHojaSig }}" class="btn btn-default btn-sm" id="btn_hoja_sig">
                        <span class="fa fa-chevron-right"></span>
                    </a>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group col-md-2 pull-right">
        <a href="{{ route('inventario.addLinea', compact('hoja')) }}" id="btn_mostrar_agregar" class="btn btn-default pull-right">
            <span class="fa fa-plus-circle"></span>
            {{ trans('inventario.button_new_line') }}
        </a>
    </div>
    {{ Form::close() }}

    {{ Form::open(['id' => 'frm_inventario', 'class'=>'form-inline']) }}

    <div class="form-group col-md-3">
        <label>{{ trans('inventario.auditor') }}</label>
        {{ $detalleInventario->first()->getFieldForm('auditor', ['id' => 'id_auditor', 'class' => 'form-control input-sm']) }}
    </div>
</div>

<div id="formulario_digitador">

    @include('orm.validation_errors')

    {{ Form::hidden('hoja', $hoja) }}
    {{ Form::hidden('auditor', $detalleInventario->first()->auditor) }}
    <table class="table table-striped table-hover table-condensed table-fixed-header">
        <thead class="header">
            <tr>
                <th class="text-center">{{ trans('inventario.digit_th_ubicacion') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_material') }}</th>
                <th class="text-left">{{ trans('inventario.digit_th_descripcion') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_lote') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_centro') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_almacen') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_UM') }}</th>
                <th class="text-right" nowrap>{{ trans('inventario.digit_th_cant_sap') }}</th>
                <th class="text-right">{{ trans('inventario.digit_th_cant_fisica') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_HU') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_observacion') }}</th>
            </tr>
        </thead>
        <tbody>
            <?php $sum_sap = 0; $sum_fisico = 0; $tab_index = 10; ?>
            @foreach ($detalleInventario as $linea)
                <tr>
                    <td class="text-center" nowrap>
                        {{ $linea->getFormattedFieldValue('ubicacion') }}

                        @if ($linea->reg_nuevo === 'S')
                            <a href="{{ route('inventario.addLinea', ['hoja' => $hoja, 'id' => $linea->id]) }}" class="btn btn-default btn-xs">
                                <span class="fa fa-edit"></span>
                            </a>
                            {{ Form::hidden('ubicacion_'   . $linea->id, $linea->ubicacion) }}
                            {{-- Form::hidden('hu_'          . $linea->id, $linea->hu) --}}
                            {{ Form::hidden('catalogo_'    . $linea->id, $linea->catalogo) }}
                            {{ Form::hidden('descripcion_' . $linea->id, $linea->descripcion) }}
                            {{ Form::hidden('lote_'        . $linea->id, $linea->lote) }}
                            {{ Form::hidden('centro_'      . $linea->id, $linea->centro) }}
                            {{ Form::hidden('almacen_'     . $linea->id, $linea->almacen) }}
                            {{ Form::hidden('um_'          . $linea->id, $linea->um) }}
                        @endif
                    </td>
                    <td class="text-center"><?= $linea->catalogo; ?></td>
                    <td class="text_left">{{ $linea->getFormattedFieldValue('descripcion') }}</td>
                    <td class="text-center">{{ $linea->getFormattedFieldValue('lote') }}</td>
                    <td class="text-center">{{ $linea->getFormattedFieldValue('centro') }}</td>
                    <td class="text-center">{{ $linea->getFormattedFieldValue('almacen') }}</td>
                    <td class="text-center">{{ $linea->um }}</td>
                    <td class="text-right">{{ fmtCantidad($linea->stock_sap) }}</td>
                    <td class="text-center col-md-1 {{ $errors->has("detalle.{$linea->id}.stock_fisico") ? 'has-error' : '' }}">
                        {{ Form::text("detalle[{$linea->id}][stock_fisico]", $linea->stock_fisico, ['class' => 'input-sm form-control text-right', 'tabindex' => $tab_index]) }}
                        {{-- form_error('stock_fisico_' . $linea->id) --}}
                    </td>
                    <td class="text-center col-md-1 {{ $errors->has("detalle.{$linea->id}.hu") ? 'has-error' : '' }}">
                        {{ Form::text("detalle[{$linea->id}][hu]", $linea->hu, ['class' => 'input-sm form-control text-right', 'tabindex' => $tab_index+100]) }}
                        {{-- form_error('hu_'.$linea->id) --}}
                    </td>
                    <td class="text-center{{ $errors->has("detalle.{$linea->id}.observacion") ? 'has-error' : '' }}">
                        {{ Form::text("detalle[{$linea->id}][observacion]", $linea->observacion, ['class' => 'input-sm form-control text-right', 'tabindex' => $tab_index+200]) }}
                    </td>
                </tr>
                <?php $sum_sap += $linea->stock_sap; $sum_fisico +=$linea->stock_fisico; $tab_index += 1; ?>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="2">
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><strong>{{ fmtCantidad($sum_sap) }}</strong></td>
                <td class="text-right"><strong>{{ fmtCantidad($sum_fisico) }}</strong></td>
                <td></td>
                <td>
                    <div class="text-right">
                        <a href="#" class="btn btn-primary" id="btn_guardar">
                            <span class="fa fa-check"></span>
                            {{ trans('inventario.digit_button_save_page') }}
                        </a>
                    </div>
                </td>
            </tr>
        </tfoot>

    </table>
    {{ Form::close() }}
</div>

<script type="text/javascript" src="{{ asset('js/view_inventario.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/reporte.js') }}"></script>
@endsection
