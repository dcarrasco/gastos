<?= form_open('','id="frm_param"'); ?>
<div class="accordion hidden-print" id="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-6">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						<span class="fa fa-filter"></span>
						{_stock_perm_panel_params_}
					</a>
				</div>

				<div class="col-md-6">
					<div class="pull-right">
						{_stock_perm_panel_date_}: {fecha_reporte}
					</div>
				</div>
			</div>
		</div>

		<div class="panel-collapse collapse in" id="form_param">
			<div class="panel-body">

				{validation_errors}

				<div class="col-md-4 form-group <?= form_has_error_class('tipo_alm[]') ?>">
					<label class="control-label">{_stock_perm_label_alm_}</label>

					<?= form_multiselect('tipo_alm[]', $combo_tipo_alm, $this->input->post('tipo_alm'), 'size="10" class="form-control"'); ?>

					<div class="pull-right">
						<div class="radio-inline">
							<?= form_radio('tipo_op', 'MOVIL', set_radio('tipo_op','MOVIL', TRUE), 'id="tipo_op_movil"'); ?>
							{_stock_perm_radio_movil_}
						</div>
						<div class="radio-inline">
							<?= form_radio('tipo_op', 'FIJA', set_radio('tipo_op','FIJA'), 'id="tipo_op_fija"'); ?>
							{_stock_perm_radio_fija_}
						</div>
					</div>
				</div>

				<div class="col-md-3 form-group">
					<label class="control-label">{_stock_perm_label_estados_}</label>
					<?= form_multiselect('estado_sap[]', $combo_estado_sap, $this->input->post('estado_sap'), 'size="10" class="form-control"'); ?>
				</div>

				<div class="col-md-2 form-group">
					<label class="control-label">{_stock_perm_label_tipmat_}</label>
					<?= form_multiselect('tipo_mat[]', $combo_tipo_mat, $this->input->post('tipo_mat'), 'size="10" class="form-control"'); ?>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">{_stock_perm_label_detalle_}</label>

						<div class="checkbox">
							<label>
								<?= form_checkbox('incl_almacen', '1', set_value('incl_almacen'), 'id="incl_almacen"'); ?>
								{_stock_perm_check_alm_}
							</label>
						</div>
						<div class="checkbox">
							<label>
								<?= form_checkbox('incl_lote', '1', set_value('incl_lote'), 'id="incl_lote"'); ?>
								{_stock_perm_check_lotes_}
							</label>
						</div>
						<div class="checkbox">
							<label>
								<?= form_checkbox('incl_modelos', '1', set_value('incl_modelos'), 'id="incl_modelos"'); ?>
								{_stock_perm_check_modelos_}
							</label>
						</div>

						<hr/>

						<div class="pull-right">
							<button type="submit" class="btn btn-primary">
								<span class="fa fa-search"></span>
								{_stock_perm_button_report_}
							</button>
						</div>
					</div>
				</div>

			</div>
		</div>

		<?= form_hidden('sort', set_value('sort','')); ?>
	</div>
</div>
<?= form_close(); ?>

<div> <!-- *************** REPORTE *************** -->
	{reporte}
</div> <!-- *************** /REPORTE *************** -->

<script type="text/javascript">
$(document).ready(function() {
	$('input[name="tipo_op"]').change(function (event) {
		$('#frm_param').submit();
	});


	$('[data-toggle="tooltip"]').tooltip();

	$('#filtrar_material').keyup(function (event) {
		$('tr.not_found').show();
		$('tr.not_found').removeClass('not_found');

		var a_buscar = $('#filtrar_material').val().toUpperCase();
		if (a_buscar != '') {
			$('div.content-module-main tr').each(function() {
				var nodo_texto = $(this).children('td:eq(1)');
				if (nodo_texto.size() > 0) {
					if (nodo_texto.html().toUpperCase().indexOf(a_buscar) == -1) {
						$(this).addClass('not_found');
					}
				}
			});
			$('tr.not_found').hide();
			$('#filtrar_material').addClass('search_found');
		} else {
			$('#filtrar_material').removeClass('search_found');
		}
	});

});
</script>