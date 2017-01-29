<?= form_open('','id="frm_ppal"'); ?>
<div class="panel panel-default hidden-print">
	<div class="panel-heading">
		<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
			<span class="fa fa-filter"></span>
			{_stock_analisis_params_}
		</a>
	</div>

	<div class="panel-collapse collapse in" id="form_param">
		<div class="panel-body">

			{validation_errors}

			<div class="col-md-4">
				<div class="form-group <?= form_has_error_class('series'); ?>">
					<label class="control-label">
						{_stock_analisis_label_series_}
					</label>
					<?= form_textarea('series', set_value('series'), 'id="series" rows="10" cols="30" class="form-control"'); ?>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>
						{_stock_analisis_label_reports_}
					</label>
					<div class="checkbox">
						<label>
							<?= form_checkbox('show_mov', 'show', set_value('show_mov', TRUE)); ?>
							{_stock_analisis_check_movimientos_}
						</label>
					</div>
					<div class="checkbox">
						<label>
							<?= form_checkbox('ult_mov', 'show', set_value('ult_mov')); ?>
							{_stock_analisis_check_filtrar_ultmov_}
						</label>
					</div>
					<div class="checkbox">
						<label>
							<?= form_checkbox('show_despachos', 'show', set_value('show_despachos')); ?>
							{_stock_analisis_check_despachos_}
						</label>
					</div>
					<div class="checkbox">
						<label>
							<?= form_checkbox('show_stock_sap', 'show', set_value('show_stock_sap')); ?>
							{_stock_analisis_check_stock_sap_}
						</label>
					</div>
					<div class="checkbox">
						<label>
							<?= form_checkbox('show_stock_scl', 'show', set_value('show_stock_scl')); ?>
							{_stock_analisis_check_stock_scl_}
						</label>
					</div>
					<div class="checkbox">
						<label>
							<?= form_checkbox('show_trafico', 'show', set_value('show_trafico')); ?>
							{_stock_analisis_check_trafico_}
							(<?= anchor($this->router->class.'/trafico_por_mes', '{_stock_analisis_link_detalle_trafico_}'); ?>)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<?= form_checkbox('show_gdth', 'show', set_value('show_gdth')); ?>
							{_stock_analisis_check_gestor_}
						</label>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-right">
					<button type="submit" name="submit" class="btn btn-primary" id="boton-submit">
						<span class="fa fa-search"></span>
						{_stock_analisis_button_query_}
					</button>
					<button name="excel" class="btn btn-default" id="boton-reset">
						<span class="fa fa-refresh"></span>
						{_stock_analisis_button_reset_}
					</button>
				</div>
			</div>

		</div>
	</div>
</div>
<?= form_close(); ?>


<?php if (set_value('show_mov')): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_movimientos" class="accordion-toggle" data-toggle="collapse">
			{_stock_analisis_title_movimientos_}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_movimientos">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_mov}
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (set_value('show_despachos')): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_despachos" class="accordion-toggle" data-toggle="collapse">
			{_stock_analisis_title_despachos_}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_despachos">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_despachos}
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (set_value('show_stock_sap')): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_stock_sap" class="accordion-toggle" data-toggle="collapse">
			{_stock_analisis_title_stock_sap_}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_stock_sap">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_stock_sap}
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (set_value('show_stock_scl')): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_stock_scl" class="accordion-toggle" data-toggle="collapse">
			{_stock_analisis_title_stock_scl_}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_stock_scl">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_stock_scl}
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (set_value('show_trafico')): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_trafico" class="accordion-toggle" data-toggle="collapse">
			{_stock_analisis_title_trafico_}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_trafico">
		<div class="accordion-inner" style="overflow: auto">
			{datos_show_trafico}
		</div>
	</div>
</div>
<?php endif; ?>


<?php if (set_value('show_gdth')): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#tabla_gdth" class="accordion-toggle" data-toggle="collapse">
			{_stock_analisis_title_gestor_}
		</a>
	</div>

	<div class="panel-body collapse in" id="tabla_gdth">
		<div class="accordion-inner" style="overflow: auto">
			<table class="table table-bordered table-striped table-hover table-condensed reporte" style="white-space:nowrap;">
			<?php foreach($datos_show_gdth as $serie_gdth): ?>
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
			<?php foreach($serie_gdth as $reg_log_gdth): ?>
				<tr>
					<td><?= $reg_log_gdth['id_log_deco_tarjeta'] ?></td>
					<td><?= $reg_log_gdth['fecha_log'] ?></td>
					<td><?= $reg_log_gdth['serie_deco'] ?></td>
					<td><?= $reg_log_gdth['serie_tarjeta'] ?></td>
					<td><?= $reg_log_gdth['peticion'] ?></td>
					<td><?= $reg_log_gdth['estado'] ?></td>
					<td><?= $reg_log_gdth['tipo_operacion_cas'] ?></td>
					<td><?= $reg_log_gdth['telefono'] ?></td>
					<td><?= $reg_log_gdth['rut'] ?></td>
					<td><?= $reg_log_gdth['nombre'] ?></td>
				</tr>
			<?php endforeach; ?>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<?php endif; ?>


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
