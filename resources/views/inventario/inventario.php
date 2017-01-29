<div class="col-md-12 well">
{validation_errors}

<?= form_open($this->router->class . '/ingreso', 'id="frm_buscar" role="form" class="form-inline"'); ?>
	<?= form_hidden('formulario','buscar'); ?>

	<div class="form-group col-md-4">
		<label>{_inventario_report_label_inventario_}</label>
		<p class="form-control-static">{nombre_inventario}</p>
	</div>

	<div class="form-group col-md-3">
		<label class="control-label">{_inventario_page_}</label>

		<div class="input-group col-md-7">
			<div class="input-group">
				<span class="input-group-btn">
					<a href="#" class="btn btn-default btn-sm" id="btn_buscar">
						<span class="fa fa-search"></span>
					</a>
				</span>
				<?= form_input('hoja', '{hoja}', 'maxlength="10" id="id_hoja" class="form-control input-sm"'); ?>
				<span class="input-group-btn">
					<a href="{link_hoja_ant}" class="btn btn-default btn-sm" id="btn_hoja_ant">
						<span class="fa fa-chevron-left"></span>
					</a>
					<a href="{link_hoja_sig}" class="btn btn-default btn-sm" id="btn_hoja_sig">
						<span class="fa fa-chevron-right"></span>
					</a>
				</span>
			</div>
		</div>
	</div>

	<div class="form-group col-md-3">
		<label class="control-label">{_inventario_auditor_}</label>
		{combo_auditores}
	</div>

	<div class="form-group col-md-2 pull-right">
		<a href="<?= site_url($this->router->class.'/editar/'.$hoja.'/'.$id_auditor) ?>" id="btn_mostrar_agregar" class="btn btn-default pull-right">
			<span class="fa fa-plus-circle"></span>
			{_inventario_button_new_line_}
		</a>
	</div>

<?= form_close(); ?>
</div>


<div id="formulario_digitador">
	<?= form_open($this->router->class . "/ingreso/$hoja/$id_auditor/".time(), 'id="frm_inventario"'); ?>
	<?= form_hidden('formulario','inventario'); ?>
	<?= form_hidden('hoja', $hoja); ?>
	<?= form_hidden('auditor', $id_auditor); ?>
	<table class="table table-striped table-hover table-condensed table-fixed-header">
		<thead class="header">
			<tr>
				<th class="text-center">{_inventario_digit_th_ubicacion_}</th>
				<th class="text-center">{_inventario_digit_th_material_}</th>
				<th class="text-left">{_inventario_digit_th_descripcion_}</th>
				<th class="text-center">{_inventario_digit_th_lote_}</th>
				<th class="text-center">{_inventario_digit_th_centro_}</th>
				<th class="text-center">{_inventario_digit_th_almacen_}</th>
				<th class="text-center">{_inventario_digit_th_UM_}</th>
				<th class="text-right" nowrap>{_inventario_digit_th_cant_sap_}</th>
				<th class="text-right">{_inventario_digit_th_cant_fisica_}</th>
				<th class="text-center">{_inventario_digit_th_HU_}</th>
				<th class="text-center">{_inventario_digit_th_observacion_}</th>
			</tr>
		</thead>
		<tbody>
			<?php $sum_sap = 0; $sum_fisico = 0;?>
			<?php $tab_index = 10; ?>
			<?php foreach ($detalle_inventario as $linea_det): ?>
				<tr>
					<td class="text-center" nowrap>
						<?= $linea_det->get_valor_field('ubicacion'); ?>

						<?php if ($linea_det->reg_nuevo === 'S'):?>
							<a href="<?= site_url($this->router->class . '/editar/' . $hoja . '/' . $id_auditor . '/' . $linea_det->id); ?>" class="btn btn-default btn-xs">
								<span class="fa fa-edit"></span>
							</a>
							<?= form_hidden('ubicacion_'   . $linea_det->id, $linea_det->ubicacion); ?>
							<?php //echo form_hidden('hu_'          . $linea_det->id, $linea_det->hu); ?>
							<?= form_hidden('catalogo_'    . $linea_det->id, $linea_det->catalogo); ?>
							<?= form_hidden('descripcion_' . $linea_det->id, $linea_det->descripcion); ?>
							<?= form_hidden('lote_'        . $linea_det->id, $linea_det->lote); ?>
							<?= form_hidden('centro_'      . $linea_det->id, $linea_det->centro); ?>
							<?= form_hidden('almacen_'     . $linea_det->id, $linea_det->almacen); ?>
							<?= form_hidden('um_'          . $linea_det->id, $linea_det->um); ?>
						<?php endif; ?>
					</td>
					<!-- <td><?php //echo $linea_det->hu; ?></td> -->
					<td class="text-center"><?= $linea_det->catalogo; ?></td>
					<td class="text_left"><?= $linea_det->get_valor_field('descripcion'); ?></td>
					<td class="text-center"><?= $linea_det->get_valor_field('lote'); ?></td>
					<td class="text-center"><?= $linea_det->get_valor_field('centro'); ?></td>
					<td class="text-center"><?= $linea_det->get_valor_field('almacen'); ?></td>
					<td class="text-center"><?= $linea_det->um; ?></td>
					<td class="text-right"><?= fmt_cantidad($linea_det->stock_sap); ?></td>
					<td class="text-center col-md-1">
						<?= form_input(
							'stock_fisico_'.$linea_det->id,
							set_value('stock_fisico_'.$linea_det->id, $linea_det->stock_fisico),
							'class="input-sm form-control text-right" tabindex="'.$tab_index.'"'
						); ?>
						<?= form_error('stock_fisico_' . $linea_det->id); ?>
					</td>
					<td class="text-center col-md-1">
						<?= form_input(
							'hu_'.$linea_det->id,
							set_value('hu_'.$linea_det->id, $linea_det->hu),
							'class="input-sm form-control" tabindex="'.($tab_index+100).'"'
						); ?>
						<?= form_error('hu_'.$linea_det->id); ?>
					</td>
					<td class="text-center">
						<?= form_input(
							'observacion_'.$linea_det->id,
							set_value('observacion_'.$linea_det->id, $linea_det->observacion),
							'class="input-sm form-control" maxlength="100" tabindex="'.($tab_index + 200).'"'
						); ?>
					</td>
				</tr>
				<?php $sum_sap += $linea_det->stock_sap; $sum_fisico += $linea_det->stock_fisico;?>
				<?php $tab_index += 1; ?>
			<?php endforeach; ?>
		</tbody>

		<!-- totales -->
		<tfoot>
			<tr>
				<td colspan="2">
				</td>
				<!-- <td></td> -->
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="text-right"><strong><?= fmt_cantidad($sum_sap); ?></strong></td>
				<td class="text-right"><strong><?= fmt_cantidad($sum_fisico); ?></strong></td>
				<td></td>
				<td>
					<div class="text-right">
						<a href="#" class="btn btn-primary" id="btn_guardar">
							<span class="fa fa-check"></span>
							{_inventario_digit_button_save_page_}
						</a>
					</div>
				</td>
			</tr>
		</tfoot>

	</table>
	<?= form_close(); ?>
</div><!-- fin content-module-main-principal -->

<script type="text/javascript" src="{base_url}js/view_inventario.js"></script>
<script type="text/javascript" src="{base_url}js/reporte.js"></script>

