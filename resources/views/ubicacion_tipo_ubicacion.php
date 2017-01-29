<div class="row">
	<div class="text-right">
		<a href="#" class="btn btn-primary" id="btn_mostrar_agregar">
			<span class="fa fa-plus-circle"></span>
			Agregar ubicacion
		</a>
	</div>
</div>

<div class="row">
	<div class="col-md-10 col-md-offset-1 well" id="form_agregar" style="display: none;">
		<?= form_open('','id=frm_agregar')?>
		<?= form_hidden('formulario','agregar'); ?>

		<div class="row">
			<div class="col-md-4">
				Tipo de Inventario
				<?= form_dropdown('agr-tipo_inventario', $combo_tipos_inventario, set_value('agr-tipo_inventario'), 'class="form-control input-sm"'); ?>
				<?= form_error('agr-tipo_inventario'); ?>
			</div>
			<div class="col-md-4">
				Ubicacion
				<?= form_multiselect('agr-ubicacion[]', array(), set_value('agr-ubicacion[]'), 'size="15" class="form-control input-sm"'); ?>
				<?= form_error('agr-ubicacion'); ?>
			</div>
			<div class="col-md-4">
				Tipo de Ubicacion
				<?= form_dropdown('agr-tipo_ubicacion', array('' => 'Seleccione tipo ubicacion...'), set_value('agr-tipo_ubicacion'), 'class="form-control input-sm"'); ?>
				<?= form_error('agr-tipo_ubicacion'); ?>
			</div>
		</div>

		<div class="row">
			<div class="pull-right">
				<a href="#" class="btn btn-primary" id="btn_agregar">
					<span class="fa fa-check"></span>
					Agregar
				</a>
			</div>
		</div>
		<?= form_close(); ?>
	</div> <!-- fin content-module-main-agregar -->
</div>

<div class="row">
	<div class="">
		<?= form_open('', 'id="frm_usuarios"'); ?>
		<?= form_hidden('formulario','editar'); ?>
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>id</th>
					<th>Tipo de Inventario</th>
					<th>Tipo de Ubicacion</th>
					<th>Ubicacion</th>
					<th>Borrar</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($datos_hoja as $reg): ?>
				<tr>
					<td><?= $reg['id']; ?></td>
					<td>
						<?= form_dropdown($reg['id'].'-tipo_inventario', $combo_tipos_inventario, set_value($reg['id'].'-tipo_inventario', $reg['tipo_inventario']), 'class="form-control input-sm"'); ?>
						<?= form_error($reg['id'].'-tipo_inventario'); ?>
					</td>
					<td>
						<?= form_dropdown($reg['id'].'-tipo_ubicacion', $combo_tipos_ubicacion[$reg['tipo_inventario']], set_value($reg['id'].'-tipo_ubicacion', $reg['id_tipo_ubicacion']), 'class="form-control input-sm"'); ?>
						<?= form_error($reg['id'].'-tipo_ubicacion'); ?>
					</td>
					<td>
						<?= form_input($reg['id'].'-ubicacion', set_value($reg['id'].'-ubicacion', $reg['ubicacion']),'maxlength="45" class="form-control input-sm"'); ?>
						<?= form_error($reg['id'].'-ubicacion'); ?>
					</td>
					<td>
						<a href="#" class="btn btn-default btn-sm" id="btn_borrar" id-borrar="<?= $reg['id']; ?>">
							<span class="fa fa-trash"></span>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?= form_close(); ?>

		<div class="text-center">
			<?= ($links_paginas != '') ? $links_paginas : ''; ?>
		</div>
	</div> <!-- fin content-module-main -->

	<div class="pull-right">
		<a href="#" class="btn btn-primary" id="btn_guardar">
			<span class="fa fa-check"></span>
			Guardar
		</a>
	</div> <!-- fin content-module-footer -->

	<?= form_open('','id="frm_borrar"'); ?>
		<?= form_hidden('formulario','borrar'); ?>
		<?= form_hidden('id_borrar'); ?>
	<?= form_close(); ?>


</div> <!-- fin content-module -->

<script type="text/javascript">
	$(document).ready(function() {
		if ($('div.content-module-main-agregar div.error').length > 0) {
			$('div.content-module-main-agregar').toggle();
		}

		$('#btn_mostrar_agregar').click(function (event) {
			event.preventDefault();
			$('div#form_agregar').toggle();
		});

		$('#btn_guardar').click(function (event) {
			event.preventDefault();
			$('form#frm_usuarios').submit();
		});

		$('#btn_agregar').click(function (event) {
			event.preventDefault();
			$('form#frm_agregar').submit();
		});

		$('a.boton-borrar').click(function (event) {
			event.preventDefault();
			var id_borrar = $(this).attr('id-borrar');
			if (confirm('Seguro que desea borrar el usuario id=' + id_borrar)) {
				$('form#frm_borrar input[name="id_borrar"]').val(id_borrar);
				$('form#frm_borrar').submit();
			}
		});

		$('form#frm_agregar select[name="agr-tipo_inventario"]').change(function() {

			var url_json_ubic = js_base_url + 'inventario_config/get_json_ubicaciones_libres/' + $(this).val() + '/' + Date.now();
			$.getJSON(url_json_ubic, function(data) {
				var items = [];
				$.each(data, function(key, val) {
					items.push('<option value="' + key + '">' + val + '</option>');
				});
				$('select[name="agr-ubicacion[]"]').empty().append(items.join(''));
			});

			var url_json_tipo = js_base_url + 'inventario_config/get_json_tipo_ubicacion/' + $(this).val() + '/' + Date.now();
			$.getJSON(url_json_tipo, function(data) {
				var items = [];
				$.each(data, function(key, val) {
					items.push('<option value="' + key + '">' + val + '</option>');
				});
				$('select[name="agr-tipo_ubicacion"]').empty().append(items.join(''));
			});
		});



	});
</script>
