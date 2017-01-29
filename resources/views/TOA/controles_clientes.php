<div class="accordion hidden-print">
	<?= form_open('','method="get" id="frm_param"'); ?>
	<div class="panel panel-default">

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						{_consumo_parametros_}
					</a>
				</div>
			</div>
		</div>

		<div class="panel-body collapse in" id="form_param">
			<div class="accordion-inner">

				{validation_errors}

				<div class="row">
					<div class="col-md-5 form_group <?= form_has_error_class('cliente') ?>">
						<label class="control-label">{_controles_clientes_}</label>
						<?= form_input('cliente', set_value('cliente'), 'class="form-control"'); ?>
					</div>

					<div class="col-md-5 form_group <?= form_has_error_class('mes') ?>">
						<label class="control-label">{_consumo_fechas_}</label>
						<?= form_date_range('fecha_desde', $this->input->get('fecha_desde'), 'fecha_hasta', $this->input->get('fecha_hasta'), 'class="form-control" data-date-min-view-mode="1"'); ?>
					</div>

					<div class="col-md-2">
						<button type="submit" class="pull-right btn btn-primary">
							<span class="fa fa-search"></span>
							{_consumo_btn_reporte_}
						</button>
					</div>

				</div>
			</div>
		</div>
	</div>
	<?= form_close(); ?>
</div>

<div class="content-module-main">
<?php if ($clientes): ?>
	<?php $num_lin= 0; ?>
	<table class="table table-hover table-condensed reporte">
	<?php foreach ($clientes as $cliente): ?>

		<?php if ($num_lin== 0): ?>
			<!-- ENCABEZADO TABLA REPORTE -->
			<thead>
			<tr>
				<th></th>
				<th>Numero cliente</th>
				<th>Nombre cliente</th>
				<th class="text-center">Numero de peticiones</th>
			</tr>
			</thead>

			<!-- CUERPO TABLA REPORTE -->
			<tbody>
		<?php endif; ?>

		<tr>
			<td class="text-muted"><?= $num_lin+ 1; ?></td>
			<td><?= fmt_rut($cliente['customer_number']); ?></td>
			<td><?= $cliente['cname']; ?></td>
			<td class="text-center"><?= anchor($link_peticiones.$cliente['customer_number'],fmt_cantidad($cliente['cantidad'])); ?></td>
		</tr>
		<?php $num_lin+= 1; ?>
	<?php endforeach; ?>
	</tbody>
</table>
<?php endif ?>

</div> <!-- fin content-module-main -->
