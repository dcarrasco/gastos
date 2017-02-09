@extends('common.app_layout')

@section('modulo')
<div class="row">
	<div class="col-md-10 col-md-offset-1 well">
		{{ Form::open(['class'=>'form-horizontal', 'role'=>'form', 'files'=>true]) }}
		<fieldset>

			<legend>{{ trans('inventario.upload_label_fieldset') }}</legend>

			<div class="form-group">
				<label class="control-label col-sm-4">{{ trans('inventario.upload_label_inventario') }}</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $inventario->id }} - {{ $inventario }}</p>
				</div>
			</div>

			<?php if (!$showScriptCarga): ?>
			<div class="form-group">
				<label class="control-label col-sm-4">{{ trans('inventario.upload_label_file') }}</label>
				<div class="col-sm-8">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<p><strong>
							<span class="fa fa-exclamation-circle"></span>
							{{ trans('inventario.upload_warning_line1') }}
						</strong></p>
						<p>
							{!! trans('inventario.upload_warning_line2') !!} "{{ $inventario }}".
						</p>
					</div>
					{{ Form::file('upload_file', ['class'=>'form-control', 'accept'=>'.txt,.csv']) }}
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4">{{ trans('inventario.upload_label_password') }}</label>
				<div class="col-sm-8">
					{{ Form::password('upload_password', ['class' => 'form-control']) }}
				</div>
			</div>
			<?php endif; ?>

			<?php if ($showScriptCarga): ?>
			<div class="form-group">
				<label class="control-label col-sm-4">{{ trans('inventario.upload_label_progress') }}</label>
				<div class="col-sm-8">
					{{ $msjError }}
					<div id="progreso_carga">
						<div class="progress">
							<div class="progress-bar" role="progressbar" style="width: 0%;"></div>
						</div>
						<div id="status_progreso1">
							{{ trans('inventario.upload_status_OK') }}
							<span id="reg_actual">0</span> / {{ $regsOK }}
						</div>
						<div id="status_progreso2">
							{{ trans('inventario.upload_status_error') }}
							<span id="reg_error">0</span>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label class="control-label col-sm-4">
				</label>
				<div class="col-sm-8">
					<?php if ($showScriptCarga): ?>
						<button class="btn btn-primary pull-right" id="ejecuta_carga">
							<span class="fa fa-play"></span>
							{{ trans('inventario.upload_button_load') }}
						</button>
					<?php else: ?>
					<button type="submit" name="submit" class="btn btn-primary pull-right" id="btn_guardar">
						<span class="fa fa-cloud-upload"></span>
						{{ trans('inventario.upload_button_upload') }}
					</button>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4">{{ trans('inventario.upload_label_format') }}</label>
				<div class="col-sm-8">
					<pre>
{{ trans('inventario.upload_format_file') }}
					</pre>
				</div>
			</div>

		</fieldset>
		{{ Form::close() }}

	</div>
</div>

<script type="text/javascript">
	var subeStock = {
		arrDatos    : [],
		arrErrores  : [],
		curr        : 0,
		cant        : 0,
		cantErrores : 0,
		cantProc    : 0,

		proc_linea_carga: function(objLinea) {
			this.arrDatos.push(objLinea);
			this.cant = this.arrDatos.length;
		},

		procesa_carga: function() {
			var sData;
			this.cantErrores = 0;
			$('#reg_error').text(this.cantErrores);

			while (this.arrDatos.length > 0) {
				sData = this.arrDatos.shift();
				this.cantProc += 1;
				if (this.cantProc == 1) {
					$('#ejecuta_carga').addClass('disabled');
				}
				this.procesa_carga_linea(sData);
			}
			this.arrDatos = this.arrErrores;
		},

		procesa_carga_linea: function(datosLinea) {
			var _this = this;
			$.ajax({
				type:  "POST",
				url:   js_base_url + "inventario/subir-linea",
				async: true,
				data:  datosLinea,
				success: function(datos) {
					_this.curr += 1;
					var progreso = parseInt(100 * _this.curr / _this.cant) + '%';
					$('div.progress-bar').css('width',progreso);
					$('div.progress-bar').text(progreso);
					$('#reg_actual').text(_this.curr);

					if (_this.curr >= _this.cant) {
						$('#status_progreso1').html('Carga finalizada (' + _this.curr + ' registros cargados)');
					}
				},
				error: function() {
					_this.cantErrores += 1;
					_this.arrErrores.push(datosLinea);
					$('#reg_error').text(_this.cantErrores);
				},
				complete: function() {
					_this.cantProc -= 1;
					if (_this.cantProc == 0) {
						$('#ejecuta_carga').removeClass('disabled');
					}
				},
			});
		},

		setCant: function() {
			this.cant = this.arrDatos.length;
		},

		getCantDatos: function() {
			return this.arrDatos.length;
		},
	}



</script>


<script type="text/javascript">
$(document).ready(function() {

	$('#ejecuta_carga').click(function (event) {
		event.preventDefault();
		while (subeStock.getCantDatos() > 0) {
			subeStock.procesa_carga();
		}
		if (subeStock.getCantDatos() == 0) {
			$('#ejecuta_carga').addClass('disabled');
		}
	})

	{!! $scriptCarga !!}

});
</script>
@endsection