@extends('common.app_layout')

@section('modulo')
<div class="col-md-12 well">
    {{ Form::open(['method' => 'GET', 'id' => 'frm_param']) }}
	<div class="col-md-6">
        <strong>{{ trans('inventario.inventario') }}:</strong> {{ $inventario }}
    </div>
    <div class="col-md-6">
        <div class="pull-right">
            {{ Form::checkbox('incl_ajustes', '1', request()->input('incl_ajustes'), ['id' => 'incl_ajustes']) }}
            {{ trans('inventario.adjust_link_hide') }}
        </div>
    </div>
    {{ Form::close() }}
</div>

@include('orm.validation_errors')

<div>
    {{ Form::open(['url' => Request::fullUrl(), 'id' => 'frm_inventario']) }}

    <table class="table table-hover table-condensed reporte table-fixed-header">

        <!-- ENCABEZADO -->
        <thead class="header">
            <tr>
                <th class="text-center">{{ trans('inventario.digit_th_material') }}</th>
                <th class="text-left">{{ trans('inventario.digit_th_descripcion') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_lote') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_centro') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_almacen') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_ubicacion') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_hoja') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_UM') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_cant_sap') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_cant_fisica') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_cant_ajuste') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_dif') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_tipo_dif') }}</th>
                <th class="text-center">{{ trans('inventario.digit_th_observacion_ajuste') }}</th>
            </tr>
        </thead>

        <!-- CUERPO -->
        <tbody>
            <?php $sum_sap = 0; $sum_fisico = 0; $sum_ajuste = 0; ?>
            <?php $subtot_sap = 0; $subtot_fisico = 0; $subtot_ajuste = 0; ?>
            <?php $tab_index = 10; ?>
            <?php $cat_ant = ''; ?>
            @foreach ($detalleAjustes as $detalle)
                <?php if ($cat_ant != $detalle->catalogo AND $cat_ant != ''): ?>
                    <tr class="active">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><strong><?= fmt_cantidad($subtot_sap, 0, TRUE); ?></strong></td>
                        <td class="text-center"><strong><?= fmt_cantidad($subtot_fisico, 0, TRUE); ?></strong></td>
                        <td class="text-center"><strong><?= fmt_cantidad($subtot_ajuste, 0, TRUE); ?></strong></td>
                        <td class="text-center"><strong><?= fmt_cantidad($subtot_fisico - $subtot_sap + $subtot_ajuste, 0, TRUE); ?></strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="15">&nbsp;</td>
                    </tr>
                    <?php $subtot_sap = 0; $subtot_fisico = 0; $subtot_ajuste = 0; ?>
                <?php endif; ?>

                <tr>
                    <td class="text-center"><?= ($cat_ant != $detalle->catalogo) ? $detalle->catalogo : ''; ?></td>
                    <td class="text-left"><?= ($cat_ant != $detalle->catalogo) ? $detalle->descripcion : ''; ?></td>
                    <td class="text-center"><?= $detalle->lote; ?></td>
                    <td class="text-center"><?= $detalle->centro; ?></td>
                    <td class="text-center"><?= $detalle->almacen; ?></td>
                    <td class="text-center"><?= $detalle->ubicacion; ?></td>
                    <!-- <td class="text-center">{{-- $detalle->hu --}}</td> -->
                    <td class="text-center"><?= $detalle->hoja; ?></td>
                    <td class="text-center"><?= $detalle->um; ?></td>
                    <td class="text-center"><?= fmt_cantidad($detalle->stock_sap); ?></td>
                    <td class="text-center"><?= fmt_cantidad($detalle->stock_fisico); ?></td>
                    <td class="{{ $errors->has('stock_ajuste_'.$detalle->id) ? 'has-error' : ''}}">
                        {{ Form::text('stock_ajuste_'.$detalle->id, $detalle->stock_ajuste, ['class' => 'form-control input-sm text-right', 'size' => 5, 'tabindex' => $tab_index]) }}
                        {{-- form_error('stock_ajuste_'.$detalle->id); --}}
                    </td>
                    <td class="text-center">
                        {{ fmt_cantidad($detalle->stock_fisico - $detalle->stock_sap + $detalle->stock_ajuste) }}
                    </td>
                    <td class="text-center">
                        <?php if (($detalle->stock_fisico - $detalle->stock_sap + $detalle->stock_ajuste) > 0): ?>
                            <button class="btn btn-default btn-sm btn-warning" style="white-space: nowrap;">
                                <span class="fa fa-question-circle"></span>
                                {{ trans('inventario.report_label_sobrante') }}
                            </button>
                        <?php elseif (($detalle->stock_fisico - $detalle->stock_sap + $detalle->stock_ajuste) < 0): ?>
                            <button class="btn btn-default btn-sm btn-danger" style="white-space: nowrap;">
                                <span class="fa fa-remove"></span>
                                {{ trans('inventario.report_label_faltante') }}
                            </button>
                        <?php else: ?>
                            <button class="btn btn-default btn-sm btn-success" style="white-space: nowrap;">
                                <span class="fa fa-check"></span>
                                {{ trans('inventario.report_label_OK') }}
                            </button>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        {{ Form::text('observacion_'.$detalle->id, $detalle->glosa_ajuste, ['class' => 'form-control input-sm', 'max_length'=>200, 'tabindex' => $tab_index + 10000]) }}
                    </td>
                </tr>
                <?php $sum_sap += $detalle->stock_sap; $sum_fisico += $detalle->stock_fisico; $sum_ajuste += $detalle->stock_ajuste?>
                <?php $subtot_sap += $detalle->stock_sap; $subtot_fisico += $detalle->stock_fisico; $subtot_ajuste += $detalle->stock_ajuste?>
                <?php $tab_index += 1; ?>
                <?php $cat_ant = $detalle->catalogo; ?>
            @endforeach

            {{-- subtotales (ultima linea) --}}
            <tr class="active">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center"><strong><?= fmt_cantidad($subtot_sap, 0, TRUE); ?></strong></td>
                <td class="text-center"><strong><?= fmt_cantidad($subtot_fisico, 0, TRUE); ?></strong></td>
                <td class="text-center"><strong><?= fmt_cantidad($subtot_ajuste, 0, TRUE); ?></strong></td>
                <td class="text-center"><strong><?= fmt_cantidad($subtot_fisico - $subtot_sap + $subtot_ajuste, 0, TRUE); ?></strong></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="15">&nbsp;</td>
            </tr>
        </tbody>

        <!-- totales -->
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <!-- <td></td> -->
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center"><strong><?= fmt_cantidad($sum_sap); ?></strong></td>
                <td class="text-center"><strong><?= fmt_cantidad($sum_fisico); ?></strong></td>
                <td class="text-center"><strong><?= fmt_cantidad($sum_ajuste); ?></strong></td>
                <td class="text-center"><strong><?= fmt_cantidad($sum_fisico - $sum_sap + $sum_ajuste); ?></strong></td>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary">
                        <span class="fa fa-check-circle"></span>
                        {{ trans('inventario.report_save') }}
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
    <script type="text/javascript" src="{{ asset('js/reporte.js') }}"></script>
    {{ Form::close() }}
</div><!-- fin content-module-main-principal -->

<div class="text-center">
    {{ $detalleAjustes->links() }}
</div>

<script type="text/javascript">
    $('#incl_ajustes').change(function () {
        $('#frm_param').submit();
    });
</script>
@endsection
