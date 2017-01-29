<div class="row">
	<div class="col-md-8 col-md-offset-2 well">

		<?= form_open('', 'class="form-horizontal" role="form"')?>
		<fieldset>

		<legend>{_inventario_form_new_}</legend>

		{validation_errors}

		<?= $detalle_inventario->form_item('ubicacion', FALSE); ?>

		<div class="form-group <?= form_has_error_class('catalogo'); ?>">
			<label class="control-label col-sm-4">
				{_inventario_form_new_material_}
				<?= $detalle_inventario->get_marca_obligatorio_field('catalogo'); ?>
			</label>
			<div class="col-sm-3">
				<div class="input-group">
					<?= form_input('agr_filtrar', set_value('agr_filtrar'), 'class="form-control" id="agr_filtrar" placeholder="{_inventario_form_new_material_placeholder_}"'); ?>
					<span class="input-group-btn">
						<div class="btn btn-default">
							<span class="fa fa-search"></span>
						</div>
					</span>
				</div>
			</div>
			<div class="col-md-5">
				<?= form_dropdown('catalogo', $arr_catalogo, '', 'class="form-control" id="agr_material"'); ?>
			</div>
		</div>

		<?= $detalle_inventario->form_item('lote', FALSE); ?>

		<?= $detalle_inventario->form_item('um', FALSE); ?>

		<?= $detalle_inventario->form_item('centro', FALSE); ?>

		<?= $detalle_inventario->form_item('almacen', FALSE); ?>

		<?= $detalle_inventario->form_item('stock_fisico', FALSE); ?>

		<?= $detalle_inventario->form_item('hu', FALSE); ?>

		<?= $detalle_inventario->form_item('observacion', FALSE); ?>

		<div class="form-group">
			<label class="control-label col-sm-4">
			</label>
			<div class="col-sm-8">

				<?php if ($id): ?>
				<div class="pull-left">
					<button type="submit" name="accion" value="borrar" class="btn btn-danger">
						<span class="fa fa-trash-o"></span>
						{_inventario_form_new_button_delete_}
					</button>
				</div>
				<?php endif; ?>

				<div class="pull-right">
					<button type="submit" name="accion" value="agregar" class="btn btn-primary">
						<span class="fa fa-check"></span>
						<?php if ( ! $id): ?>
							{_inventario_form_new_button_add_}
						<?php else: ?>
							{_inventario_form_new_button_edit_}
						<?php endif; ?>
					</button>
					<a href="<?= site_url($this->router->class.'/ingreso/'.$hoja); ?>" class="btn btn-default">
						<span class="fa fa-ban"></span>
						{_inventario_form_new_button_cancel_}
					</a>
				</div>
			</div>
		</div>

		</fieldset>
		<?= form_close(); ?>
	</div> <!-- fin content-module-main-agregar -->
</div>

<script type="text/javascript" src="{base_url}js/view_inventario.js"></script>
