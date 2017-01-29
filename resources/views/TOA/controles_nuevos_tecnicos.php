<div class="row">
	<div class="col-md-offset-1 col-md-10">
		{msg_agregar}

		<?php if (count($nuevos_tecnicos) > 0): ?>
			<table class="table table-striped table-hover table-condensed">
				<thead>
					<tr>
						<th>id tecnico</th>
						<th>tecnico</th>
						<th>rut</th>
						<th>id empresa</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($nuevos_tecnicos as $tecnico): ?>
					<tr>
						<td><?= $tecnico['id_tecnico']; ?></td>
						<td><?= $tecnico['tecnico']; ?></td>
						<td><?= $tecnico['rut']; ?></td>
						<td><?= $tecnico['id_empresa']; ?></td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>

			<?php if (empty($msg_agregar)): ?>
				<?= form_open('','class="form-horizontal"'); ?>
				<?= form_hidden('agregar', 'agregar'); ?>
				<div class="form-group text-right">
					<button name="submit" type="submit" class="btn btn-primary" id="btn_imprimir" {update_status}>
						<span class="fa fa-user-plus"></span>
						{_toa_controles_nuevos_tecnicos_}
					</button>
				</div>
				<?= form_close(); ?>
			<?php endif ?>

		<?php else: ?>
			<?= print_message($this->lang->line('toa_controles_sin_tecnicos')); ?>
		<?php endif ?>
	</div>
</div>
