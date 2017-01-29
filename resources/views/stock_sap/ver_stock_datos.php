{tabla_stock}

<?php if ($datos_grafico): ?>
<div class="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="#panel_graficos" class="accordion-toggle" data-toggle="collapse">
				{_stock_sap_panel_graph_}
			</a>
		</div>
		<div class="panel-body collapse" id="panel_graficos">
			<div class="row">
				<div class="col-md-4">
					<div>
						<strong>{_stock_sap_label_mostrar_mat_}</strong>
					</div>
					<div>
						<label class="checkbox-inline">
							<?= form_radio('sel_graph_tipo', 'equipos', set_radio('sel_graph_tipo', 'equipos'), 'id="sel_graph_tipo_equipos"'); ?>
							{_stock_sap_radio_equipos_}
						</label>
						<label class="checkbox-inline">
							<?= form_radio('sel_graph_tipo', 'simcard', set_radio('sel_graph_tipo', 'simcard'), 'id="sel_graph_tipo_simcard"'); ?>
							{_stock_sap_radio_sim_}
						</label>
						<label class="checkbox-inline">
							<?= form_radio('sel_graph_tipo', 'otros', set_radio('sel_graph_tipo', 'otros'), 'id="sel_graph_tipo_otros"'); ?>
							{_stock_sap_radio_otros_}
						</label>
					</div>
					<div>
						<strong>Mostrar tipo de dato</strong>
					</div>
					<div>
						<label class="checkbox-inline">
							<?= form_radio('sel_graph_valor', 'cantidad', set_radio('sel_graph_valor','cantidad'), 'id="sel_graph_valor_cantidad"'); ?>
							Cantidad
						</label>
						<label class="checkbox-inline">
							<?= form_radio('sel_graph_valor', 'monto', set_radio('sel_graph_valor','monto'), 'id="sel_graph_valor_monto"'); ?>
							Monto
						</label>
					</div>
				</div>
				<div class="col-md-8">
					<div style="width:600px; margin-left:auto; margin-right:auto;">
						<div id="chart" class="jqplot-target" style="width: 100%; height: 450px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{base_url}js/jqplot/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/view_stock_datos.js"></script>

<script language="javascript">
	var data_grafico = {
		q_equipos: <?= $datos_grafico['serie_q_equipos']; ?>,
		v_equipos: <?= $datos_grafico['serie_v_equipos']; ?>,
		q_simcard: <?= $datos_grafico['serie_q_simcard']; ?>,
		v_simcard: <?= $datos_grafico['serie_v_simcard']; ?>,
		q_otros: <?= $datos_grafico['serie_q_otros']; ?>,
		v_otros: <?= $datos_grafico['serie_v_otros']; ?>,
		x_label: <?= $datos_grafico['str_eje_x']; ?>,
		series_label: <?= $datos_grafico['str_label_series']; ?>,
	}

	$(document).ready(function(){
		$('input:radio[name=sel_graph_tipo], input:radio[name=sel_graph_valor]').click(function (event) {
			render_grafico(data_grafico, $('input:radio[name=sel_graph_tipo]:checked').val(), $('input:radio[name=sel_graph_valor]:checked').val());
		});

	});
</script>

<?php endif ?>