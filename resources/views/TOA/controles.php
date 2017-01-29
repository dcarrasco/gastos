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
					<div class="col-md-3 form_group <?= form_has_error_class('empresa') ?>">
						<label class="control-label">{_controles_tecnicos_empresas_}</label>
						<?= form_dropdown('empresa', $combo_empresas, set_value('empresa'), 'class="form-control"'); ?>
					</div>

					<div class="col-md-2 form_group <?= form_has_error_class('mes') ?>">
						<label class="control-label">{_controles_tecnicos_meses_}</label>
						<?= form_month('mes', set_value('mes'), 'class="form-control"'); ?>
					</div>

					<div class="col-md-3 form_group <?= form_has_error_class('filtro_trx') ?>">
						<label class="control-label">{_controles_tecnicos_filtro_trx_}</label>
						<?= form_dropdown('filtro_trx', $combo_filtro_trx, set_value('filtro_trx'), 'class="form-control"'); ?>
					</div>

					<div class="col-md-2 form_group <?= form_has_error_class('dato') ?>">
						<label class="control-label">{_controles_tecnicos_dato_desplegar_}</label>
						<?= form_dropdown('dato', $combo_dato_desplegar, set_value('dato'), 'class="form-control"'); ?>
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
<?php if ($control): ?>
	<?php $num_lin = 0; $tot_col = array(); $count_col = array();?>
	<table class="table table-bordered table-hover table-condensed reporte">

	<?php foreach ($control as $id_tecnico => $datos): ?>

		<?php if ($num_lin == 0): ?>
			<!-- ENCABEZADO TABLA REPORTE -->
			<thead>
			<tr class="active">
				<th></th>
				<th>Ciudad</th>
				<th>T&eacute;cnico</th>
				<?php foreach ($datos['actuaciones'] as $dia_act => $cant_act): ?>
					<th class="text-center">
						<?= $this->toa_model->dias_de_la_semana[date('w', strtotime($anomes.$dia_act))]; ?><br>
						<?= $dia_act; ?>
						<?php $tot_col[$dia_act] = 0; $count_col[$dia_act] = 0; ?>
					</th>
				<?php endforeach; ?>
				<?php $count_col['total'] = 0; ?>
				<th class="text-center">Tot Mes</th>
			</tr>
			</thead>

			<!-- CUERPO TABLA REPORTE -->
			<tbody>
		<?php endif; ?>

		<tr>
			<td class="text-muted"><?= $num_lin + 1; ?></td>
			<td class="text-center"><?= $datos['ciudad']; ?></td>
			<td style="white-space: nowrap;"><?= $id_tecnico; ?> - <?= $datos['nombre']; ?> (<?= fmt_rut($datos['rut']); ?>)</td>

			<?php $tot_lin = 0; ?>
			<?php foreach ($datos['actuaciones'] as $dia_act => $cant_act): ?>
				<?php if ($cant_act): ?>
					<td class="text-center info"><?= anchor($url_detalle_dia.'/'.$anomes.$dia_act.'/'.$anomes.$dia_act.'/'.$id_tecnico, fmt_cantidad($cant_act)); ?></td>
				<?php else: ?>
					<td></td>
				<?php endif ?>
				<?php $tot_lin += $cant_act; $tot_col[$dia_act] += $cant_act; $count_col[$dia_act] += $cant_act ? 1 : 0;?>
			<?php endforeach; ?>

			<th class="text-center"><?= fmt_cantidad($tot_lin); ?></th>
			<?php $count_col['total'] += $tot_lin ? 1 : 0;?>
		</tr>
		<?php $num_lin += 1; ?>
	<?php endforeach; ?>
	</tbody>

	<!-- PIE TABLA REPORTE -->
	<tfoot>
		<tr class="active">
			<th></th>
			<th></th>
			<th></th>
			<?php $tot_lin = 0; ?>
			<?php foreach ($tot_col as $dia_act => $total): ?>
				<th class="text-center"><?= fmt_cantidad($total); ?><?php $tot_lin += $total ?></th>
			<?php endforeach; ?>
			<th class="text-center"><?= fmt_cantidad($tot_lin); ?></th>
		</tr>
		<tr class="active">
			<th></th>
			<th></th>
			<th></th>
			<?php $tot_lin = 0; ?>
			<?php foreach ($count_col as $dia_act => $count): ?>
				<?php $porcentaje = $count / $num_lin; ?>
				<th class="text-center <?= $this->toa_model->clase_cumplimiento_consumos($porcentaje) ?>"><?= fmt_cantidad(100*$porcentaje, 0, TRUE); ?>%</th>
			<?php endforeach; ?>
		</tr>
	</tfoot>
</table>

<?php endif ?>
</div> <!-- fin content-module-main -->
