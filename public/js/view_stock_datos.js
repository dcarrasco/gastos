var plot,

	jq_grafico = function(div_id, datos, x_label, y_label, series_label, title) {
		return $.jqplot(div_id, datos, {
			title: title,
			animate: true,
			animateReplot: true,
			stackSeries: true,
			captureRightClick: true,
			seriesDefaults:{
				renderer:$.jqplot.BarRenderer,
				rendererOptions: {
					highlightMouseOver: true,
					animation: {
						speed: 300
					}
				},
				pointLabels: {show: true}
			},
			series: series_label,
			axes: {
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer,
					ticks: x_label
				},
				yaxis: {
					padMin: 0,
					min: 0,
					tickOptions: {formatString: "%'i"},
					label: y_label,
					labelRenderer: $.jqplot.CanvasAxisLabelRenderer
				}
			},
			legend: {
				show: true,
				location: 's',
				placement: 'outsideGrid'
			}
		});
	},

	render_grafico = function(data_grafico, tipo, datos) {
		var data,
			str_tipo = '',
			str_dato = '',
			str_ejey = '';

		str_dato = (datos == 'monto') ? 'Valor (MM$)' : 'Cantidad';

		if (tipo == 'simcard')
		{
			str_tipo = 'Simcard';
			data = (datos == 'monto') ? data_grafico.v_simcard : data_grafico.q_simcard;
		}
		else if (tipo == 'otros')
		{
			str_tipo = 'Otros';
			data = (datos == 'monto') ? data_grafico.v_otros : data_grafico.q_otros;
		}
		else
		{
			str_tipo = 'Equipos';
			data = (datos == 'monto') ? data_grafico.v_equipos : data_grafico.q_equipos;
		}

		if (plot !== undefined) plot.destroy();

		plot = jq_grafico('chart', data, data_grafico.x_label, str_dato, data_grafico.series_label, str_dato + ' ' + str_tipo);
	};
