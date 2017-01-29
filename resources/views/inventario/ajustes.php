<div class="col-md-12 well">
	<div class="col-md-6">
		<strong>{_inventario_inventario_}:</strong> {inventario}
	</div>
	<div class="col-md-6">
		<div class="pull-right">
			<?= anchor(
				"{$this->router->class}/ajustes?ocultar_reg=".(($ocultar_reg === 0) ? '1' : '0').'&page='.$pag,
				($ocultar_reg === 0) ? '{_inventario_adjust_link_hide_}' : '{_inventario_adjust_link_show_}'
			); ?>
		</div>
	</div>
</div>

<div>
	<?= form_open($url_form, 'id="frm_inventario"'); ?>
	<?= form_hidden('formulario','ajustes'); ?>

	<table class="table table-hover table-condensed reporte table-fixed-header">

		<!-- ENCABEZADO -->
		<thead class="header">
			<tr>
				<th class="text-center">{_inventario_digit_th_material_}</th>
				<th class="text-left">{_inventario_digit_th_descripcion_}</th>
				<th class="text-center">{_inventario_digit_th_lote_}</th>
				<th class="text-center">{_inventario_digit_th_centro_}</th>
				<th class="text-center">{_inventario_digit_th_almacen_}</th>
				<th class="text-center">{_inventario_digit_th_ubicacion_}</th>
				<th class="text-center">{_inventario_digit_th_hoja_}</th>
				<th class="text-center">{_inventario_digit_th_UM_}</th>
				<th class="text-center">{_inventario_digit_th_cant_sap_}</th>
				<th class="text-center">{_inventario_digit_th_cant_fisica_}</th>
				<th class="text-center">{_inventario_digit_th_cant_ajuste_}</th>
				<th class="text-center">{_inventario_digit_th_dif_}</th>
				<th class="text-center">{_inventario_digit_th_tipo_dif_}</th>
				<th class="text-center">{_inventario_digit_th_observacion_ajuste_}</th>
			</tr>
		</thead>

		<!-- CUERPO -->
		<tbody>
			<?php $sum_sap = 0; $sum_fisico = 0; $sum_ajuste = 0; ?>
			<?php $subtot_sap = 0; $subtot_fisico = 0; $subtot_ajuste = 0; ?>
			<?php $tab_index = 10; ?>
			<?php $cat_ant = ''; ?>
			<?php foreach ($detalle_ajustes as $detalle): ?>
				<?php if ($cat_ant != $detalle->catalogo AND $cat_ant != ''): ?>
					<tr class="active">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="text-center"><strong><?= fmt_cantidad($subtot_sap, 0, TRUE); ?></strong></td>
						<td class="text-center"><strong><?= fmt_cantidad($subtot_fisico, 0, TRUE); ?></strong></td>
						<td class="text-center"><strong><?= fmt_cantidad($subtot_ajuste, 0, TRUE); ?></strong></td>
						<td class="text-center"><strong><?= fmt_cantidad($subtot_fisico - $subtot_sap + $subtot_ajuste, 0, TRUE); ?></strong></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>
					<?php $subtot_sap = 0; $subtot_fisico = 0; $subtot_ajuste = 0; ?>
				<?php endif; ?>

				<tr>
					<td class="text-center"><?= ($cat_ant != $detalle->catalogo) ? $detalle->catalogo : ''; ?></td>
					<td class="text-left"><?= ($cat_ant != $detalle->catalogo) ? $detalle->descripcion : ''; ?></td>
					<td class="text-center"><?= $detalle->lote; ?></td>
					<td class="text-center"><?= $detalle->centro; ?></td>
					<td class="text-center"><?= $detalle->almacen; ?></td>
					<td class="text-center"><?= $detalle->ubicacion; ?></td>
					<!-- <td class="text-center"><?php //echo $detalle->hu; ?></td> -->
					<td class="text-center"><?= $detalle->hoja; ?></td>
					<td class="text-center"><?= $detalle->um; ?></td>
					<td class="text-center"><?= fmt_cantidad($detalle->stock_sap); ?></td>
					<td class="text-center"><?= fmt_cantidad($detalle->stock_fisico); ?></td>
					<td>
						<?= form_input('stock_ajuste_'.$detalle->id, set_value('stock_ajuste_'.$detalle->id, $detalle->stock_ajuste), 'class="form-control input-sm text-right" size="5" tabindex="'.$tab_index.'"'); ?>
						<?= form_error('stock_ajuste_'.$detalle->id); ?>
					</td>
					<td class="text-center">
						<?= fmt_cantidad($detalle->stock_fisico - $detalle->stock_sap + $detalle->stock_ajuste); ?>
					</td>
					<td class="text-center">
						<?php if (($detalle->stock_fisico - $detalle->stock_sap + $detalle->stock_ajuste) > 0): ?>
							<button class="btn btn-default btn-sm btn-warning" style="white-space: nowrap;">
								<span class="fa fa-question-circle"></span>
								{_inventario_report_label_sobrante_}
							</button>
						<?php elseif (($detalle->stock_fisico - $detalle->stock_sap + $detalle->stock_ajuste) < 0): ?>
							<button class="btn btn-default btn-sm btn-danger" style="white-space: nowrap;">
								<span class="fa fa-remove"></span>
								{_inventario_report_label_faltante_}
							</button>
						<?php else: ?>
							<button class="btn btn-default btn-sm btn-success" style="white-space: nowrap;">
								<span class="fa fa-check"></span>
								{_inventario_report_label_OK_}
							</button>
						<?php endif; ?>
					</td>
					<td class="text-center">
						<?= form_input('observacion_'.$detalle->id, set_value('observacion_'.$detalle->id, $detalle->glosa_ajuste), 'class="form-control input-sm" max_length="200" tabindex="'.($tab_index + 10000).'"'); ?>
					</td>
				</tr>
				<?php $sum_sap += $detalle->stock_sap; $sum_fisico += $detalle->stock_fisico; $sum_ajuste += $detalle->stock_ajuste?>
				<?php $subtot_sap += $detalle->stock_sap; $subtot_fisico += $detalle->stock_fisico; $subtot_ajuste += $detalle->stock_ajuste?>
				<?php $tab_index += 1; ?>
				<?php $cat_ant = $detalle->catalogo; ?>
			<?php endforeach; ?>

			<!-- subtotales (ultima linea) -->
			<tr class="active">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="text-center"><strong><?= fmt_cantidad($subtot_sap, 0, TRUE); ?></strong></td>
				<td class="text-center"><strong><?= fmt_cantidad($subtot_fisico, 0, TRUE); ?></strong></td>
				<td class="text-center"><strong><?= fmt_cantidad($subtot_ajuste, 0, TRUE); ?></strong></td>
				<td class="text-center"><strong><?= fmt_cantidad($subtot_fisico - $subtot_sap + $subtot_ajuste, 0, TRUE); ?></strong></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="15">&nbsp;</td>
			</tr>
		</tbody>

		<!-- totales -->
		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<!-- <td></td> -->
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="text-center"><strong><?= fmt_cantidad($sum_sap); ?></strong></td>
				<td class="text-center"><strong><?= fmt_cantidad($sum_fisico); ?></strong></td>
				<td class="text-center"><strong><?= fmt_cantidad($sum_ajuste); ?></strong></td>
				<td class="text-center"><strong><?= fmt_cantidad($sum_fisico - $sum_sap + $sum_ajuste); ?></strong></td>
				<td></td>
				<td>
					<button type="submit" class="btn btn-primary">
						<span class="fa fa-check-circle"></span>
						{_inventario_report_save_}
					</button>
				</td>
			</tr>
		</tfoot>
	</table>
	<script type="text/javascript" src="{base_url}js/reporte.js"></script>
	<?= form_close(); ?>
</div><!-- fin content-module-main-principal -->

<div class="text-center">
	<?= ($links_paginas != '') ? $links_paginas : ''; ?>
</div>
