@extends('common.app_layout')

@section('modulo')
<div class="accordion">
	{{ Form::open(['id'=>'frm_param', 'class'=>'form-inline']) }}
	{{ Form::hidden('sort', request('sort','')) }}
	<div class="panel panel-default">

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						{{ trans('toa.consumo_parametros') }}
					</a>
				</div>
			</div>
		</div>

		<div class="panel-body collapse in" id="form_param">
			<div class="accordion-inner">

                @include('orm.validation_errors')

				<div class="row">
					<div class="col-md-4 form_group {{ $errors->has('reporte') ? 'has-error' : '' }}">
						<label class="control-label">{{ trans('toa.consumo_reporte') }}</label>
						{{ Form::select('reporte', $reportes, request('reporte'), ['class'=>'form-control']) }}
					</div>

					<div class="col-md-6 form_group {{ ($errors->has('fecha_desde') or $errors->has('fecha_hasta')) ? 'has-error' : '' }}">
						<label class="col-md-4 control-label">{{ trans('toa.consumo_fechas') }}</label>
						<div class="col-md-8">
                            <div class="input-group input-daterange">
                                <span class="input-group-addon">Desde</span>

                                {{ Form::text('fecha_desde', request('fecha_desde'), ['class'=>'form-control', 'data-provide'=>'datepicker', 'data-date-today-highlight'=>'true', 'data-date-language'=>'es', 'data-date-autoclose'=>'true']) }}
                                <span class="input-group-addon">Hasta</span>
                                {{ Form::text('fecha_hasta', request('fecha_hasta'), ['class'=>'form-control', 'data-provide'=>'datepicker', 'data-date-today-highlight'=>'true', 'data-date-language'=>'es', 'data-date-autoclose'=>'true']) }}
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('input[name="fecha_desde"]').on('changeDate', function(e) {
                                        var fecha_desde = $('input[name="fecha_desde"]').val();
                                        $('input[name="fecha_hasta"]').val(fecha_desde);
                                        $('input[name="fecha_hasta"]').datepicker('setStartDate',fecha_desde);
                                    })
                                });
                            </script>
						</div>
					</div>

					<div class="col-md-2">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary">
								<span class="fa fa-search"></span>
								{{ trans('toa.consumo_btn_reporte') }}
							</button>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	{{ Form::close() }}
</div>

<div class="content-module-main">
{!! $reporteConsumo !!}
</div> <!-- fin content-module-main -->
@endsection
