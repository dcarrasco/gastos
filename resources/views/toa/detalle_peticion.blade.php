@extends('common.app_layout')

@section('modulo')
<div>
	@if ( ! $peticion)
	<div class="col-md-10 col-md-offset-1 well">
		{{ Form::open(['class'=>'form-horizontal']) }}
		<fieldset>

			<legend>{{ trans('toa.consumo_peticion_legend') }}</legend>

	        @include('orm.validation_errors')

			<div class="form-group {{ $errors->has('peticion') ? 'has-error' : ''}}">
				<label class="control-label col-sm-4">{{ trans('toa.consumo_peticion_label') }}</label>
				<div class="col-sm-8">
					{{ Form::text('peticion', request('peticion'), ['class'=>'form-control']) }}
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4">
				</label>
				<div class="col-sm-8">
					<button name="submit" type="submit" class="btn btn-primary pull-right" id="btn_buscar">
						<span class="fa fa-search"></span>
						{{ trans('toa.consumo_peticion_search_button') }}
					</button>
				</div>
			</div>

		</fieldset>
		{{ Form::close() }}
	</div>
	@else
	<div class="col-md-7 well form-horizontal">
		<fieldset>
			<legend>Datos petici&oacute;n</legend>
			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">ID Petici&oacute;n</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.appt_number') }}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">Fecha</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ fmt_fecha(array_get($peticion, 'peticionToa.date')) }}
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">RUT</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ fmt_rut(array_get($peticion, 'peticionToa.customer_number')) }}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">Nombre</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.cname') }}
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">Tel&eacute;fono</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.cphone') }}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">Celular</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.ccell') }}
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">e-mail</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.cemail') }}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">Direcci&oacute;n</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.caddress') }}
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">Agencia</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.XA_ORIGINAL_AGENCY') }}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">Ciudad</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.ccity') }}
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">Tecnolog&iacute;as</label>
				<div class="col-sm-10 col-xs-9">
					<p class="form-control-static">
						<span class="label label-default">BA</span><span class="label label-info">{{ array_get($peticion, 'peticionToa.XA_BROADBAND_TECHNOLOGY') }}</span>
						<span class="label label-default">STB</span><span class="label label-info">{{ array_get($peticion, 'peticionToa.XA_TELEPHONE_TECHNOLOGY') }}</span>
						<span class="label label-default">TV</span><span class="label label-info">{{ array_get($peticion, 'peticionToa.XA_TV_TECHNOLOGY') }}</span>
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">Empresa</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.empresa') }}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">T&eacute;cnico</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.Resource_External_ID') }}
						{{ array_get($peticion, 'peticionToa.Resource_Name') }}
					</p>
				</div>
			</div>

			<div class="form-group-sm">
				<label class="control-label col-sm-2 col-xs-3">Tipo de trabajo</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{!! $tipoTrabajo->mostrarInfo() !!}
					</p>
				</div>
				<label class="control-label col-sm-2 col-xs-3">Origen Peticion</label>
				<div class="col-sm-4 col-xs-9">
					<p class="form-control-static">
						{{ array_get($peticion, 'peticionToa.XA_CHANNEL_ORIGIN') }}
					</p>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="col-md-5">
		{!! $googleMap !!}
	</div>

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="#panel_sap" class="accordion-toggle" data-toggle="collapse">
					<span class="fa fa-list"></span>
					{{ trans('toa.consumo_peticion_panel_sap') }}
				</a>
			</div>
			<div class="panel-body collapse in" id="panel_sap">
			@if (count($peticion['materialesSap']) > 0)
				<table class="table table-striped table-hover table-condensed reporte">
					<thead>
						<tr>
							<th class="text-center">item</th>
							<th class="text-center">cod mat</th>
							<th class="text-left">material</th>
							<th class="text-center">serie</th>
							<th class="text-center">centro</th>
							<th class="text-center">lote</th>
							<th class="text-center">valor</th>
							<th class="text-center">doc SAP</th>
							<th class="text-center">mov SAP</th>
							<th class="text-center">desc mov SAP</th>
							<th class="text-center">PEP</th>
							<th class="text-center">unidad</th>
							<th class="text-center">cantidad</th>
							<th class="text-center">monto</th>
						</tr>
					</thead>
					<tbody>
					<?php $sum_cant = 0; $sum_monto = 0; ?>
					@foreach ($peticion['materialesSap'] as $linea_detalle)
						<tr>
							<td class="text-center text-muted">{{ $loop->iteration }}</td>
							<td class="text-center">{{ $linea_detalle->material }}</td>
							<td class="text-left">{{ $linea_detalle->texto_material }}</td>
							<td class="text-center">
								<a href="#" class="detalle-serie" data-serie="{{ $linea_detalle->serie_toa }}">
									{{ $linea_detalle->serie_toa }}
								</a>
							</td>
							<td class="text-center">{{ $linea_detalle->centro }}</td>
							<td class="text-center">{{ $linea_detalle->lote }}</td>
							<td class="text-center">{{ $linea_detalle->valor }}</td>
							<td class="text-center">{{ $linea_detalle->documento_material }}</td>
							<td class="text-center">{{ $linea_detalle->codigo_movimiento }}</td>
							<td class="text-center">{{ $linea_detalle->texto_movimiento }}</td>
							<td class="text-center">{{ $linea_detalle->elemento_pep }}</td>
							<td class="text-center">{{ $linea_detalle->umb }}</td>
							<td class="text-center"><?= fmtCantidad($linea_detalle->cant); ?></td>
							<td class="text-center"><?= fmt_monto($linea_detalle->monto); ?></td>
						</tr>
						<?php $sum_cant += $linea_detalle->cant; $sum_monto += $linea_detalle->monto; ?>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="text-center">{{ fmtCantidad($sum_cant) }}</th>
							<th class="text-center">{{ fmt_monto($sum_monto) }}</th>
						</tr>
					</tfoot>
				</table>
			@endif
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="#panel_toa" class="accordion-toggle" data-toggle="collapse">
					<span class="fa fa-list"></span>
					{{ trans('toa.consumo_peticion_panel_toa') }}
				</a>
			</div>
			<div class="panel-body collapse in" id="panel_toa">
			@if (count($peticion['materialesToa']) > 0)
				<table class="table table-striped table-hover table-condensed reporte">
					<thead>
						<tr>
							<th class="text-center">item</th>
							<th class="text-center">cod mat</th>
							<th class="text-left">material</th>
							<th class="text-center">serie</th>
							<th class="text-center">fecha instalacion</th>
							<th class="text-center">Lote</th>
							<th class="text-center">Pool inventario</th>
							<th class="text-center">cantidad</th>
						</tr>
					</thead>
					<tbody>
					<?php $sum_cant = 0; ?>
					@foreach ($peticion['materialesToa'] as $linea_detalle)
						<tr>
							<td class="text-center text-muted">{{ $loop->iteration }}</td>
							<td class="text-center">{{ $linea_detalle->XI_SAP_CODE }}</td>
							<td class="text-left">{{ $linea_detalle->XI_SAP_CODE_DESCRIPTION }}</td>
							<td class="text-center">
								<a href="#" class="detalle-serie" data-serie="{{ $linea_detalle->invsn }}">
									{{ $linea_detalle->invsn }}
								</a>
							</td>
							<td class="text-center">{{ $linea_detalle->I_INSTALL_DATE }}</td>
							<td class="text-center">{{ $linea_detalle->XI_BULK_SAP }}</td>
							<td class="text-center">{{ $linea_detalle->invpool }}</td>
							<td class="text-center">{{ fmtCantidad($linea_detalle->quantity) }}</td>
						</tr>
						<?php $sum_cant += $linea_detalle->quantity;?>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="text-center">{{ fmtCantidad($sum_cant) }}</th>
						</tr>
					</tfoot>
				</table>
			@endif
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="#panel_vpi" class="accordion-toggle" data-toggle="collapse">
					<span class="fa fa-list"></span>
					{{ trans('toa.consumo_peticion_panel_vpi') }}
				</a>
			</div>
			<div class="panel-body collapse in" id="panel_vpi">
			@if (count($peticion['materialesVpi']) > 0)
				<table class="table table-striped table-hover table-condensed reporte">
					<thead>
						<tr>
							<th class="text-center">item</th>
							<th class="text-center">ID PS</th>
							<th class="text-left">descripcion PS</th>
							<th class="text-center">ID Op Comercial</th>
							<th class="text-center">descripcion Op Comercial</th>
							<th class="text-center">cantidad</th>
						</tr>
					</thead>
					<tbody>
					<?php $sum_cant = 0; ?>
					@foreach ($peticion['materialesVpi'] as $linea_detalle)
						<tr>
							<td class="text-center text-muted">{{ $loop->iteration }}</td>
							<td class="text-center">{{ $linea_detalle->ps_id }}</td>
							<td class="text-left">{{ $linea_detalle->desc_producto_servicio }}</td>
							<td class="text-center">{{ $linea_detalle->cod_opco }}</td>
							<td class="text-center">{{ $linea_detalle->desc_operacion_comercial }}</td>
							<td class="text-center">{{ fmtCantidad($linea_detalle->pspe_cantidad) }}</td>
						</tr>
						<?php $sum_cant += $linea_detalle->pspe_cantidad; ?>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="text-center"><?= fmtCantidad($sum_cant) ?></th>
						</tr>
					</tfoot>
				</table>
			@endif
			</div>
		</div>
	</div>

	@endif
</div>

{{ Form::open(['id'=>'id_detalle_serie']) }}
{{ Form::hidden('series', '1') }}
{{ Form::hidden('show_mov', 'show') }}
{{ Form::close() }}

<script>
$(document).ready(function () {

$('a.detalle-serie').click(function(event) {
	event.preventDefault();
	$('#id_detalle_serie > input[name="series"]').val($(this).data('serie'));
	$('#id_detalle_serie').submit();
});

});
</script>
@endsection
