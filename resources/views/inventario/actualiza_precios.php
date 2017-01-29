<div class="row">
	<div class="col-md-10 col-md-offset-1 well">
		<?= form_open('','class="form-horizontal"'); ?>
		<?= form_hidden('actualizar', 'actualizar'); ?>
		<?php if ($update_status === ' disabled'): ?>
		<div class="form-group">
			<div class="col-sm-12 text-center">
				<?= sprintf('{_inventario_act_precios_msg_}', fmt_cantidad($cant_actualizada)); ?>
			</div>
		</div>
		<?php endif; ?>
		<div class="form-group">
			<div class="col-sm-12 text-center">
				<button name="submit" type="submit" class="btn btn-primary" id="btn_imprimir" {update_status}>
					<span class="fa fa-refresh"></span>
					{_inventario_act_precios_button_}
				</button>
			</div>
		</div>
		<?= form_close(); ?>
	</div>
</div>
