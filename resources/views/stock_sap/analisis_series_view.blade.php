@extends('common.app_layout')

@section('modulo')
{{ Form::open(['id'=>'frm_ppal']) }}
<div class="panel panel-default hidden-print">
	<div class="panel-heading">
		<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
			<span class="fa fa-filter"></span>
			{{ trans('stock.analisis_params') }}
		</a>
	</div>

	<div class="panel-collapse collapse in" id="form_param">
		<div class="panel-body">

            @include('orm.validation_errors')

			<div class="col-md-4">
				<div class="form-group {{ $errors->has('series') ? 'has-error' : '' }}">
					<label class="control-label">
						{{ trans('stock.analisis_label_series') }}
					</label>
					{{ Form::textarea('series', old('series'), ['id'=>'series', 'rows'=>10, 'cols'=>30, 'class'=>'form-control']) }}
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>
						{{ trans('stock.analisis_label_reports') }}
					</label>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('show_mov', 'show', old('show_mov', TRUE)) }}
							{{ trans('stock.analisis_check_movimientos') }}
						</label>
					</div>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('ult_mov', 'show', old('ult_mov')) }}
							{{ trans('stock.analisis_check_filtrar_ultmov') }}
						</label>
					</div>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('show_despachos', 'show', old('show_despachos')) }}
							{{ trans('stock.analisis_check_despachos') }}
						</label>
					</div>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('show_stock_sap', 'show', old('show_stock_sap')) }}
							{{ trans('stock.analisis_check_stock_sap') }}
						</label>
					</div>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('show_stock_scl', 'show', old('show_stock_scl')) }}
							{{ trans('stock.analisis_check_stock_scl') }}
						</label>
					</div>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('show_trafico', 'show', old('show_trafico')) }}
							{{ trans('stock.analisis_check_trafico') }}
							({{-- anchor($this->router->class.'/trafico_por_mes', trans('stock.analisis_link_detalle_trafico')) --}}
						</label>
					</div>
					<div class="checkbox">
						<label>
							{{ Form::checkbox('show_gdth', 'show', old('show_gdth')) }}
							{{ trans('stock.analisis_check_gestor') }}
						</label>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-right">
					<button type="submit" name="submit" class="btn btn-primary" id="boton-submit">
						<span class="fa fa-search"></span>
						{{ trans('stock.analisis_button_query') }}
					</button>
					<button name="excel" class="btn btn-default" id="boton-reset">
						<span class="fa fa-refresh"></span>
						{{ trans('stock.analisis_button_reset') }}
					</button>
				</div>
			</div>

		</div>
	</div>
</div>
{{ Form::close() }}


@if (request()->input('show_mov'))
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_movimientos" class="accordion-toggle" data-toggle="collapse">
			{{ trans('stock.analisis_title_movimientos') }}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_movimientos">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_mov}
		</div>
	</div>
</div>
@endif

@if (request()->input('show_despachos'))
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_despachos" class="accordion-toggle" data-toggle="collapse">
			{{ trans('stock.analisis_title_despachos') }}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_despachos">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_despachos}
		</div>
	</div>
</div>
@endif

@if (request()->input('show_stock_sap'))
<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#tabla_stock_sap" class="accordion-toggle" data-toggle="collapse">
            {{ trans('stock.analisis_title_stock_sap') }}
        </a>
    </div>

    <div class="panel-body collapse in" id="tabla_stock_sap">
        <div class="accordion-inner" style="overflow: auto">
            {datos_show_stock_sap}
        </div>
    </div>
</div>
@endif

@if (request()->input('show_stock_scl'))
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_stock_scl" class="accordion-toggle" data-toggle="collapse">
			{{ trans('stock.analisis_title_stock_scl') }}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_stock_scl">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_stock_scl}
		</div>
	</div>
</div>
@endif

@if (request()->input('show_trafico'))
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_trafico" class="accordion-toggle" data-toggle="collapse">
			{{ trans('stock.analisis_title_trafico') }}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_trafico">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_trafico}
		</div>
	</div>
</div>
@endif

@if (request()->input('show_gdth'))
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_gdth" class="accordion-toggle" data-toggle="collapse">
			{{ trans('stock.analisis_title_gestor') }}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_gdth">
		<div class="accordion-inner" style="overflow: auto">
			<table class="table table-bordered table-striped table-hover table-condensed reporte" style="white-space:nowrap;">
			@foreach($datos_show_gdth as $serie_gdth)
				<tr>
					<th>id</th>
					<th>fecha</th>
					<th>serie deco</th>
					<th>serie tarjeta</th>
					<th>peticion</th>
					<th>estado</th>
					<th>tipo operacion cas</th>
					<th>telefono</th>
					<th>rut</th>
					<th>nombre cliente</th>
				</th>
			@foreach($serie_gdth as $reg_log_gdth)
				<tr>
					<td>{{ $reg_log_gdth['id_log_deco_tarjeta'] }}</td>
					<td>{{ $reg_log_gdth['fecha_log'] }}</td>
					<td>{{ $reg_log_gdth['serie_deco'] }}</td>
					<td>{{ $reg_log_gdth['serie_tarjeta'] }}</td>
					<td>{{ $reg_log_gdth['peticion'] }}</td>
					<td>{{ $reg_log_gdth['estado'] }}</td>
					<td>{{ $reg_log_gdth['tipo_operacion_cas'] }}</td>
					<td>{{ $reg_log_gdth['telefono'] }}</td>
					<td>{{ $reg_log_gdth['rut'] }}</td>
					<td>{{ $reg_log_gdth['nombre'] }}</td>
				</tr>
            @endforeach
			@endforeach
			</table>
		</div>
	</div>
</div>
@endif


<script type="text/javascript">
	$(document).ready(function() {
		if ($("#series").val() != "")
		{
			//$("#form_param").collapse();
		}

		$("#boton-reset").click(function(event) {
			//event.preventDefault();
			$("#series").val("");
			$("#series").focus();
		})

	});
</script>
@endsection