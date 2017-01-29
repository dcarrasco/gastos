<?= form_open('','id="frm_param"'); ?>
<?= form_hidden('sort', set_value('sort','')); ?>

<div class="accordion" id="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-6">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						<span class="fa fa-filter"></span>
						{_stock_movhist_panel_params_}
					</a>
				</div>
			</div>
		</div>

		<div class="panel-collapse collapse in" id="form_param">
			<div class="panel-body">

				{validation_errors}

				<div class="col-md-2 form-group <?= form_has_error_class('fechas[]')?>">
					<label class="control-label">{_stock_movhist_label_dates_}</label>

					<div class="input-group">
						<?= form_dropdown('tipo_fecha', $combo_tipo_fecha, $this->input->post('tipo_fecha'), 'class="form-control"'); ?>
						<div class="input-group-btn">
							<button id="filtro_tipo_fecha" class="btn btn-default" type="button">
								{_stock_movhist_button_filter_}
							</button>
						</div>
					</div>
					<?= form_hidden('tipo_fecha_filtro', set_value('tipo_fecha_filtro')) ?>

					<?= form_multiselect('fechas[]', $combo_fechas, $this->input->post('fechas'), 'size="8" class="form-control"'); ?>
				</div>

				<div class="col-md-3 form-group <?= form_has_error_class('cmv[]')?>">
					<label class="control-label">{_stock_movhist_label_movs_}</label>

					<?= form_multiselect('cmv[]', $combo_cmv, $this->input->post('cmv'), 'size="10" class="form-control"'); ?>
				</div>

				<div class="col-md-3 form-group <?= form_has_error_class('almacenes[]')?>">
					<label class="control-label">{_stock_movhist_label_alm_}</label>

					<div class="input-group">
						<?= form_dropdown('tipo_alm', $combo_tipo_alm, $this->input->post('tipo_alm'), 'class="form-control"'); ?>
						<div class="input-group-btn">
							<button id="filtro_tipo_alm" class="btn btn-default" type="button">
								{_stock_movhist_button_filter_}
							</button>
						</div>
					</div>

					<?= form_multiselect('almacenes[]', $combo_almacenes, $this->input->post('almacenes'), 'size="8" class="form-control"'); ?>

					<div class="text-right">
						<div class="radio-inline">
							<?= form_radio('tipo_cruce_alm', 'alm', set_radio('tipo_cruce_alm', 'alm', TRUE)); ?>
							{_stock_movhist_radio_alm_}
						</div>

						<div class="radio-inline">
							<?= form_radio('tipo_cruce_alm', 'rec', set_radio('tipo_cruce_alm', 'rec')); ?>
							{_stock_movhist_radio_rec_}
						</div>
					</div>
				</div>

				<div class="col-md-4 form-group <?= form_has_error_class('materiales[]')?>">
					<label class="control-label">{_stock_movhist_label_mats_}</label>

					<div class="input-group">
					<?= form_dropdown('tipo_mat', $combo_tipo_mat, $this->input->post('tipo_mat'), 'class="form-control"'); ?>
						<div class="input-group-btn">
							<button id="filtro_tipo_mat" class="btn btn-default" type="button">
								{_stock_movhist_button_filter_}
							</button>
						</div>
					</div>

					<?= form_multiselect('materiales[]', $combo_materiales, $this->input->post('materiales'), 'size="8" class="form-control"'); ?>
				</div>

				<div class="col-md-12 text-right">
					<button type="submit" name="submit" class="btn btn-primary">
						<span class="fa fa-search"></span>
						{_stock_movhist_button_report_}
					</button>
				</div>

			</div>
		</div>
	</div>
</div>
<?= form_close(); ?>

<div> <!-- *************** REPORTE *************** -->
	{reporte}
</div> <!-- *************** /REPORTE *************** -->

<script type="text/javascript">
$(document).ready(function() {
	var url_ajax = js_base_url + 'stock_reporte/'

	$('select[name="tipo_fecha"]').change(function (event) {
		var url = url_ajax + 'movhist_ajax_fechas/' + $(this).val();
		actualizaCombo($('select[name="fechas[]"]'), url);
	});

	$('select[name="tipo_alm"]').change(function (event) {
		var url = url_ajax + 'movhist_ajax_almacenes/' + $(this).val();
		actualizaComboOrden($('select[name="almacenes[]"]'), url);
	});

	$('select[name="tipo_mat"]').change(function (event) {
		var url = url_ajax + 'movhist_ajax_materiales/' + $(this).val();
		actualizaCombo($('select[name="materiales[]"]'), url);
	});

	$('#filtro_tipo_fecha').click(function (event) {
		var sel_tipo_fecha = $('select[name="tipo_fecha"]'),
			sel_fechas = $('select[name="fechas[]"]'),
			val_tipo_fecha = sel_tipo_fecha.val(),
			val_fechas = sel_fechas.val();

		$('input[name="tipo_fecha_filtro"]').val(JSON.stringify(sel_fechas.val()));

		if (val_fechas !== null && val_tipo_fecha !== 'DIA')
		{
			var url = url_ajax + 'movhist_ajax_tipo_fecha/' + val_tipo_fecha;
			actualizaCombo(sel_tipo_fecha, url);

			var url = url_ajax + 'movhist_ajax_fechas/' + sel_tipo_fecha.val() + '/' + val_fechas.toString().replace(/,/g, '~');
			actualizaCombo(sel_fechas, url);

			$(this).removeClass('btn-default').addClass('btn-success');
		}
	});

	$('#filtro_tipo_alm').click(function (event) {
		var sel_tipo_alm = $('select[name="tipo_alm"]'),
			sel_almacenes = $('select[name="almacenes[]"]');

		if (sel_almacenes.val() !== null && sel_tipo_alm.val() !== 'MOVIL-ALM' && sel_tipo_alm.val() !== 'FIJA-ALM')
		{
			var url = url_ajax + 'movhist_ajax_tipo_alm/' + sel_tipo_alm.val();
			actualizaCombo(sel_tipo_alm, url);

			var url = url_ajax + 'movhist_ajax_almacenes/' + sel_tipo_alm.val() + '/' + sel_almacenes.val().toString().replace(/,/g, '~');
			actualizaCombo(sel_almacenes, url);

		$(this).removeClass('btn-default').addClass('btn-success');

		}
	});

	$('#filtro_tipo_mat').click(function (event) {
		var sel_tipo_mat = $('select[name="tipo_mat"]'),
			sel_materiales = $('select[name="materiales[]"]');

		if (sel_materiales.val() !== null && sel_tipo_mat.val() !== 'MATERIAL')
		{
			var url = url_ajax + 'movhist_ajax_tipo_mat/' + sel_tipo_mat.val();
			actualizaCombo(sel_tipo_mat, url);

			var url = url_ajax + 'movhist_ajax_materiales/' + sel_tipo_mat.val() + '/' + sel_materiales.val().toString().replace(/,/g, '~');
			actualizaCombo(sel_materiales, url);

		$(this).removeClass('btn-default').addClass('btn-success');

		}
	});

	function actualizaCombo(elem, url) {
		$.ajax({
			dataType: "json",
			url: url,
			async: false,
			success: function(data) {
				var items = [];

				$.each(data, function(key, val) {
					items.push('<option value="' + key + '">' + val + '</option>');
				});

				elem.empty().append(items.join(''));
			},
		});
	}

	function actualizaComboOrden(elem, url) {
		$.ajax({
			dataType: "json",
			url: url,
			async: false,
			success: function(data) {
				var items = [];
				var values = [];

				for (var i in data) {
					values.push({key: i, value: data[i]})
				}
				values.sort(function (a, b) {return a.value.localeCompare(b.value)});

				$.each(values, function(key, val) {
					items.push('<option value="' + val.key + '">' + val.value + '</option>');
				});

				elem.empty().append(items.join(''));
			},
		});
	}


});
</script>