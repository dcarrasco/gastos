<div class="col-md-12">
	<div class="col-md-10 col-md-offset-1 well">

	<?php $nlinea = 0; ?>
	<?php foreach ($reporte as $linea_detalle): ?>

		<?php if ($nlinea === 0): ?>
		<fieldset>
			<legend>Detalle petici&oacute;n</legend>

			<div class="form-group row">
				<label class="control-label col-md-2">Documento Material</label>
				<div class="col-md-4">
					<p class="form-control-static">
						<?= $linea_detalle['documento_material']; ?>
					</p>
				</div>
				<label class="control-label col-md-2">Fecha</label>
				<div class="col-md-4">
					<p class="form-control-static">
						<?= fmt_fecha($linea_detalle['fecha']); ?>
					</p>
				</div>
			</div>

			<div class="form-group row">
				<label class="control-label col-md-2">Empresa</label>
				<div class="col-md-4">
					<p class="form-control-static">
						<?= $linea_detalle['empresa']; ?>
					</p>
				</div>
				<label class="control-label col-md-2">T&eacute;cnico</label>
				<div class="col-md-4">
					<p class="form-control-static">
						<?= $linea_detalle['cliente']; ?> -
						<?= $linea_detalle['tecnico']; ?>
					</p>
				</div>
			</div>
		</fieldset>
<hr>
		<fieldset>
			<legend>Detalle materiales</legend>

			<table class="table table-striped reporte">
				<thead>
					<tr>
						<th class="text-center">n</th>
						<th class="text-center">centro</th>
						<th class="text-center">almacen</th>
						<th class="text-center">cod mat</th>
						<th class="text-left">material</th>
						<th class="text-center">lote</th>
						<th class="text-center">valor</th>
						<th class="text-center">unidad</th>
						<th class="text-center">cantidad</th>
						<th class="text-center">monto</th>
						<th class="text-center">mov SAP</th>
						<th class="text-center">PEP</th>
						<th class="text-center">doc SAP</th>
					</tr>
				</thead>
				<tbody>
		<?php endif; ?>

					<tr>
						<td class="text-center text-muted"><?= $nlinea+1; ?></td>
						<td class="text-center"><?= $linea_detalle['centro']; ?></td>
						<td class="text-center"><?= $linea_detalle['almacen']; ?></td>
						<td class="text-center"><?= $linea_detalle['material']; ?></td>
						<td class="text-left"><?= $linea_detalle['texto_material']; ?></td>
						<td class="text-center"><?= $linea_detalle['lote']; ?></td>
						<td class="text-center"><?= $linea_detalle['valor']; ?></td>
						<td class="text-center"><?= $linea_detalle['umb']; ?></td>
						<td class="text-center"><?= fmt_cantidad($linea_detalle['cant']); ?></td>
						<td class="text-center"><?= fmt_monto($linea_detalle['monto']); ?></td>
						<td class="text-center"><?= $linea_detalle['codigo_movimiento']; ?></td>
						<td class="text-center"><?= $linea_detalle['elemento_pep']; ?></td>
						<td class="text-center"><?= $linea_detalle['documento_material']; ?></td>
					</tr>
		<?php $nlinea += 1; ?>
	<?php endforeach; ?>
				</tbody>
			</table>

		</fieldset>
	</div>
</div>
