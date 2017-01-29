<div class="print-heading">
	<table>
		<tr>
			<td>Fecha: ___________________________</td>
			<td><h2><?= 'Inventario: ' . $nombre_inventario; ?></h2></td>
			<td class="ar"><h2>Hoja: <?= $hoja; ?></h2></td>
		</tr>
		<tr>
			<td colspan="2">Auditor: _______________________________</td>
			<td class="ar">Digitador: _______________________________</td>
		</tr>
		<tr>
			<td colspan="2">Operario: ____________________________</td>
			<td><strong>F</strong>: FRENTE / <strong>A</strong>: ATRAS</td>
		</tr>
	</table>
</div> <!-- fin print-heading -->

<br>

<div class="print-main">
	<table>
		<thead>
			<tr>
				<th class="ac">ubic</th>
				<!-- <th class="ac">hu</th> -->
				<th class="ac">material</th>
				<th class="ac" style="width: 30%">descripcion</th>
				<th class="ac">lote</th>
				<th class="ac">cen</th>
				<th class="ac">alm</th>
				<th class="ac">UM</th>

				<?php if (!$oculta_stock_sap): ?>
					<th class="ac">cant <br> sap</th>
				<?php endif; ?>

				<th class="ac">cant <br> fisico</th>

				<?php if (!$oculta_stock_sap): ?>
					<th class="ac">F</th>
					<th class="ac">A</th>
				<?php endif; ?>

				<th class="ac" style="width: 8%">HU</th>
				<th class="ac" style="width: 18%">observacion</th>
			</tr>
		</thead>
		<tbody>
			<?php $sum_sap = 0; ?>
			<?php $lin = 0; ?>
			<?php foreach ($datos_hoja as $detalle): ?>
				<?php $catalogo_rel = $detalle->get_relation_object('catalogo'); ?>
				<?php $lin += 1; ?>
				<tr>
					<td class="ac"><?= $detalle->ubicacion; ?></td>
					<!-- <td class="ac"><?php //echo $detalle->hu; ?></td> -->
					<td class="ac"><?= $detalle->catalogo; ?></td>
					<td><?= $detalle->descripcion; ?></td>
					<td class="ac"><?= $detalle->lote; ?></td>
					<td class="ac"><?= $detalle->centro; ?></td>
					<td class="ac"><?= $detalle->almacen; ?></td>
					<td class="ac"><?= $detalle->um; ?></td>

					<?php if (!$oculta_stock_sap): ?>
						<td class="ac"><?= fmt_cantidad($detalle->stock_sap); ?></td>
					<?php endif; ?>

					<!-- cantidad física -->
					<td></td>

					<?php if (!$oculta_stock_sap): ?>
						<!-- FRENTE -->
						<td></td>
						<!-- ATRAS -->
						<td></td>
					<?php endif; ?>

					<!-- HU -->
					<td></td>

					<!-- OBSERVACION -->
					<td>
						<?php if ($catalogo_rel->es_seriado): ?>
							<strong>[HU]</strong>
						<?php endif; ?>
					</td>
				</tr>
				<?php $sum_sap += (int) $detalle->stock_sap; ?>
			<?php endforeach; ?>

			<?php for($i=$lin; $i<20; $i++): ?>
				<tr>
					<td>&nbsp;</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php if (!$oculta_stock_sap): ?>
						<td></td>
						<td></td>
						<td></td>
					<?php endif; ?>
				</tr>
			<?php endfor; ?>
			<!-- totales -->
			<?php if (!$oculta_stock_sap): ?>
				<tr>
					<td colspan="7" class="no-border"></td>
					<td class="ac"><strong><?= fmt_cantidad($sum_sap); ?></strong></td>
					<td colspan="4" class="no-border"></td>
				</tr>
			<?php endif; ?>

			<tr>
				<?php if (!$oculta_stock_sap): ?>
					<td class="no-border"></td>
				<?php endif; ?>
				<td colspan="8" class="ar no-border">TOTAL HOJA: _____________</td>
				<td colspan="3" class="no-border"></td>
			</tr>


		</tbody>
	</table>
</div> <!-- fin print-main -->

