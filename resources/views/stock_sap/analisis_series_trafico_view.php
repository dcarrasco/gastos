<div class="panel-group" id="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="#form_panel" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
				Parametros consulta
			</a>
		</div>
		<div class="panel-collapse collapse in" id="form_panel">
			<div class="panel-body">
				<?= form_open(''); ?>
				<div class="col-md-4">
					<div class="form-group">
						<label>Series</label>
						<?= form_textarea(array(
								'id' => 'series',
								'name' => 'series',
								'rows' => '10',
								'cols' => '30',
								'value' => set_value('series'),
								'class' => 'form-control',
							)); ?>
						<label class="radio-inline">
							<?= form_radio('sel_tipo', 'imei', set_radio('sel_tipo', 'imei', TRUE)); ?>
							IMEI
						</label>
						<label class="radio-inline">
							<?= form_radio('sel_tipo', 'celular', set_radio('sel_tipo', 'celular')); ?>
							Celular
						</label>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label>Meses</label>
						<?= form_multiselect('meses[]', $combo_mes, $this->input->post('meses'),'size="12" class="form-control"'); ?>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group pull-right">
						<button type="submit" name="btn_submit" class="btn btn-primary" id="boton-submit">
							<span class="fa fa-search"></span>
							Consultar
						</button>
						<a href="<?= $this->router->class; ?>" class="btn btn-default">
							<span class="fa fa-chevron-left"></span>
							Volver
						</a>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>

<div class="panel-group" id="trafico">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="#form_trafico" class="accordion-toggle" data-toggle="collapse" data-parent="#trafico">
				Detalle trafico
			</a>
		</div>
		<div class="panel-collapse collapse in" id="form_trafico">
			<div class="panel-body">
				<div id="res_movimientos" style="overflow: auto">
					<table class="table table-hover table-condensed table-striped">
						<thead>
							<tr>
								<th>Serie IMEI</th>
								<th>celular</th>
								<th>Tipo</th>
								<th>RUT</th>
								<th>Nombre</th>
								<th>cod situacion</th>
								<th>Fecha Alta</th>
								<th>Fecha Baja</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#boton-submit").click(function(event) {
			event.preventDefault();

			var str_series = $('form textarea').val(),
				arr_meses  = $('form select').val(),
				str_tipo   = $('form input[type="radio"]:checked').val();

			if ((str_series != '') && (arr_meses != null))
			{
				var str_meses  = ''
				for (var i=0; i<arr_meses.length; i++)
				{
					if (i>0)
					{
						str_meses += '-';
					}
					str_meses += arr_meses[i];
				}
				$('#res_movimientos').empty();
				str_tabla = '<table class="table table-hover table-condensed table-striped"><thead><tr>';
				str_tabla += '<th>Serie IMEI</th><th>celular</th><th>Tipo</th><th>RUT</th><th>Nombre</th><th>cod situacion</th>';
				str_tabla += '<th>Fecha Alta</th><th>Fecha Baja</th>';
				for (var j=0; j<arr_meses.length; j++)
					{
						str_tabla += '<th>' + arr_meses[j] + '</th>';
					}
				str_tabla += '</tr></thead><tbody>';
				$('#res_movimientos').append(str_tabla);

				var arr_series = str_series.split('\n');
				for (var i = 0; i<arr_series.length; i++)
				{
					var serie = arr_series[i];
					if(serie != '')
					{
						$.getJSON('{base_url}<?= $this->router->class; ?>/ajax_trafico_mes/' + serie + '/' + str_meses + '/' + str_tipo, function(data) {
							for (var i=0; i<data.length; i++)
							{
								var str_append = '';
								str_append += '<tr>';
								str_append += '<td>' + data[i]['imei'] + '</td>';
								str_append += '<td>' + data[i]['celular'] + '</td>';
								str_append += '<td>' + data[i]['tipo'] + '</td>';
								str_append += '<td>' + data[i]['rut'] + '</td>';
								str_append += '<td>' + data[i]['nombre'] + '</td>';
								str_append += '<td>' + data[i]['cod_situacion'] + '</td>';
								str_append += '<td>' + data[i]['fecha_alta'] + '</td>';
								str_append += '<td>' + data[i]['fecha_baja'] + '</td>';

								for (var j=0; j<arr_meses.length; j++)
								{
									var valor_trafico = (typeof data[i][arr_meses[j]] === 'undefined') ? '' : data[i][arr_meses[j]];
									str_append += '<td>' + valor_trafico + '</td>';
								}

								str_append += '</tr>';
								$('#res_movimientos table').append(str_append);
							}
						});
					}
				}
				str_fin_tabla = '</tbody></table>';
				$('#res_movimientos').append(str_fin_tabla);
			}
			//$("form").submit();
		})

	});
</script>