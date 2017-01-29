<?= form_open('', 'id="form_param"'); ?>
<div class="accordion hidden-print">
	<div class="panel panel-default">

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						<span class="fa fa-filter"></span>
						{_stock_sap_panel_params_}
					</a>
				</div>
			</div>
		</div>

		<div class="panel-collapse collapse in" id="form_param">
			<div class="panel-body">

				<div class="col-md-3">
					<div class="form-group">
						<label>
							{_stock_clasif_label_tipoop_}
						</label>
						<?= form_dropdown('operacion', $combo_operacion, $tipo_op,'id="select_operacion" class="form-control"'); ?>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>
							{_stock_clasif_label_fechas_}
						</label>
						<?= form_multiselect('fechas[]', $combo_fechas, $fechas,'size="10" id="select_fecha" class="form-control"'); ?>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<div class="checkbox">
							<label>
								<?= form_checkbox('sel_borrar', 'borrar', set_value('sel_borrar')); ?>
								{_stock_clasif_label_delete_}
							</label>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="pull-right">
						<button type="submit" id="btn_submit" name="submit" class="btn btn-primary">
							<span class="fa fa-search"></span>
							{_stock_sap_button_report_}
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>

</div>
<?= form_close(); ?>

<?php $total = array(); ?>
<table class="table table-striped table-hover table-condensed reporte">
	<thead>
		<tr>
			<th>tipo op</th>
			<th>orden</th>
			<th>clasificacion</th>
			<th>tipo</th>
			<?php if (count($reporte['fechas'])>0): ?>
				<?php foreach ($reporte['fechas'] as $fec): ?>
					<th class="text-right"><?= $fec; ?></th>
					<?php $total[$fec] = 0; ?>
				<?php endforeach ?>
			<?php endif ?>
		</tr>
	</thead>

	<tbody>
		<?php if (count($reporte['datos']) > 0): ?>
		<?php foreach ($reporte['datos'] as $lin): ?>
		<tr>
			<td><?= $lin['tipo_op'] ?></td>
			<td><?= $lin['orden'] ?></td>
			<td><?= $lin['clasificacion'] ?></td>
			<td><?= $lin['tipo'] ?></td>
			<?php foreach ($reporte['fechas'] as $fec): ?>
				<td class="text-right"><?= fmt_monto($lin[$fec]); ?></td>
				<?php $total[$fec] += $lin[$fec]; ?>
			<?php endforeach ?>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>

	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<?php foreach ($reporte['fechas'] as $fec): ?>
				<th class="text-right"><?= fmt_monto($total[$fec]); ?></th>
				<?php $total[$fec] = 0; ?>
			<?php endforeach ?>
		</tr>
	</tfoot>
</table>

<div id="donutchart" style="width: 900px; height: 500px;"></div>

<script type="text/javascript">
$('#select_operacion').change(function() {
	tipo_op = $('#select_operacion').val();
	var url_datos = js_base_url + 'stock_sap/ajax_fechas/' + tipo_op;
	$.get(url_datos, function (data) {$('#select_fecha').html(data); });
});
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
	var data = google.visualization.arrayToDataTable({reporte_js});

	var options = {
	title: 'Stock {tipo_op}',
	pieHole: 0.6,
	slices: {js_slices},
	};

	var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
	chart.draw(data, options);
}
</script>