<div class="content-module-main">
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th>Centro</th>
				<th>Almacen</th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($almacenes as $alm): ?>
			<tr>
				<td><?= $alm['centro']; ?></td>
				<td><?= $alm['cod_bodega']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>

	</table>
</div> <!-- fin content-module-main -->
