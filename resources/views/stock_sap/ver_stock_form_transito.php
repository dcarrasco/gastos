<?= form_open(); ?>
<div class="panel-group hidden-print" id="accordion">
	<div class="panel panel-default">

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						<span class="fa fa-filter"></span>
						{_stock_sap_panel_params_}
					</a>
				</div>
				<div class="col-md-4">
				</div>
			</div>
		</div>

		<div class="panel-collapse collapse in" id="form_param">
			<div class="panel-body">

				{validation_errors}

				<div class="col-md-4 form-group <?= form_has_error_class('fecha[]') ?>">
					<label class="control-label">{_stock_sap_label_dates_}</label>
					<div class="radio">
						<label>
							<?= form_radio('sel_fechas', 'ultimodia', set_radio('sel_fechas','ultimodia', TRUE)); ?>
							{_stock_sap_radio_date1_}
						</label>
					</div>
					<div class="radio">
						<label>
							<?= form_radio('sel_fechas', 'todas', set_radio('sel_fechas','todas')); ?>
							{_stock_sap_radio_date2_}
						</label>
					</div>
					<?= form_multiselect('fecha[]', $combo_fechas_todas, $this->input->post('fecha'),'id="select_fechas" size="10" class="form-control"'); ?>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">{_stock_sap_label_mats_}</label>
						<div class="checkbox">
							<label>
								<?= form_checkbox('tipo_stock', 'tipo_stock', set_value('tipo_stock')); ?>
								{_stock_sap_check_tipstock_}
							</label>
						</div>
						<div class="checkbox">
							<label>
								<?= form_checkbox('material', 'material', set_value('material')); ?>
								{_stock_sap_check_mat_}
							</label>
						</div>
						<div class="checkbox">
							<label>
								<?= form_checkbox('lote', 'lote', set_value('lote')); ?>
								{_stock_sap_check_lotes_}
							</label>
						</div>
					</div>
					<hr/>
					<div class="form-group">
						<label class="control-label">{_stock_sap_label_mostrar_}</label>
						<div class="radio">
							<label>
								<?= form_radio('mostrar_cant_monto', 'cantidad', TRUE); ?>
								{_stock_sap_radio_cant_}
							</label>
						</div>
						<div class="radio">
							<label>
								<?= form_radio('mostrar_cant_monto', 'monto'); ?>
								{_stock_sap_radio_monto_}
							</label>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label></label>
						<div class="pull-right">
							<button type="submit" name="submit" class="btn btn-primary">
								<span class="fa fa-search"></span>
								{_stock_sap_button_report_}
							</button>
							<button type="submit" name="excel" class="btn btn-default">
								<span class="fa fa-file-text-o"></span>
								{_stock_sap_button_export_}
							</button>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div> <!-- panel panel-default -->
</div> <!-- panel-group -->
<?= form_close(); ?>

<script type="text/javascript" src="{base_url}js/ver_stock_form.js"></script>
