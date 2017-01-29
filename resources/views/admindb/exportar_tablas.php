<?= form_open(); ?>
<div class="accordion hidden-print">
	<div class="panel panel-default">

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						{_adminbd_exportar_params_}
					</a>
				</div>
			</div>
		</div>

		<div class="panel-collapse collapse in" id="form_param">
			<div class="panel-body">

				{validation_errors}

				<div class="col-md-4">
					<div class="form-group">
						<label>{_adminbd_exportar_label_tables_}</label>
						<?= form_dropdown('tabla', $combo_tablas, $this->input->post('tabla'),'id="id_select_tabla" size="10" class="form-control" onchange="'.form_onchange('select_tabla', 'select_campo', 'adminbd_exportartablas/ajax_campos').'"'); ?>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label>{_adminbd_exportar_label_fields_}</label>
						<?= form_dropdown('campo', $combo_campos, $this->input->post('campo'),'id="id_select_campo" size="7" class="form-control"'); ?>

						<label>{_adminbd_exportar_label_fields_filter_}</label>
						<?= form_input('filtro', $this->input->post('filtro'),'id="filtro_campo" class="form-control"'); ?>
					</div>
				</div>

				<div class="col-md-4">
					<div class="pull-right">
						<button type="submit" name="submit" class="btn btn-primary">
							<span class="fa fa-cloud-download"></span>
							{_adminbd_exportar_button_submit_}
						</button>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<?= form_close(); ?>

<pre>
{result_string}
</pre>
