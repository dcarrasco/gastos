<div class="col-md-12 well hidden-print">

	{validation_errors}

	<?= form_open('','id="frm_param" class="form-inline"'); ?>
	<?= form_hidden('sort', set_value('sort','')); ?>

	<div class="form-group col-md-5">
		<label for="sel_inv_activo">{_inventario_report_label_inventario_}</label>
		<?= form_dropdown('inv_activo', $combo_inventarios, $id_inventario, 'id="sel_inv_activo" class="form-control input-sm"'); ?>
	</div>

	<div class="form-group col-md-5">
		<label class="checkbox-inline">
			<?= form_checkbox('elim_sin_dif', '1', set_value('elim_sin_dif'), 'id="elim_sin_dif"'); ?>
			{_inventario_report_check_ocultar_regs_}
		</label>
		<label class="checkbox-inline">
			<?= form_checkbox('incl_ajustes', '1', set_value('incl_ajustes'), 'id="incl_ajustes"'); ?>
			{_inventario_report_check_incluir_ajustes_}
		</label>
		<label class="checkbox-inline">
			<?= form_checkbox('incl_familias', '1', set_value('incl_familias'), 'id="incl_familias"'); ?>
			{_inventario_report_check_incluir_familias_}
		</label>
	</div>

	<div class="form-group col-md-2">
		<div class="input-group input-group-sm">
			<div class="input-group">
				<span class="input-group-addon" id="btn_filtrar">
					<span class="fa fa-search"></span>

				</span>
				<?= form_input('filtrar_material', set_value('filtrar_material'), 'class="form-control input-sm" id="filtrar_material" placeholder="{_inventario_report_filter_}" onKeyPress="return event.keyCode!=13"'); ?>
			</div>
		</div>
	</div>
	<?= form_close(); ?>
</div>

<div>
	{reporte}
</div> <!-- fin content-module-main -->



<script type="text/javascript">
$(document).ready(function() {
	$('#sel_inv_activo').change(function () {
		$('form').submit();
	});

	$('#incl_ajustes').change(function () {
		$('form').submit();
	});

	$('#incl_familias').change(function () {
		$('form').submit();
	});

	$('#elim_sin_dif').change(function () {
		$('form').submit();
	});

	$('#filtrar_material').keyup(function (event) {
		var a_buscar = $('#filtrar_material').val().toUpperCase();

		if (a_buscar.length > 2) {
			$('tr.not_found').show();
			$('tr.not_found').removeClass('not_found');
			$('table.reporte tr').each(function() {
				var nodo_texto1 = $(this).children('td:eq(1)'),
					nodo_texto2 = $(this).children('td:eq(2)'),
					nodo_texto;

				if (nodo_texto1.size() > 0 || nodo_texto2.size() > 0) {
					nodo_texto = nodo_texto1.html() + nodo_texto2.html();
					if (nodo_texto.toUpperCase().indexOf(a_buscar) == -1) {
						$(this).addClass('not_found');
					}
				}
			});
			$('tr.not_found').hide();
			$('#filtrar_material').addClass('search_found');
			$('#btn_filtrar').addClass('search_found');
		} else {
			$('#filtrar_material').removeClass('search_found');
			$('#btn_filtrar').removeClass('search_found');
			$('tr.not_found').show();
			$('tr.not_found').removeClass('not_found');
		}
	});

});
</script>
