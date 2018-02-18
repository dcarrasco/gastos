$(document).ready(function() {

	if ($('#formulario_agregar div.alert-danger').length > 0) {
		$('#formulario_agregar').toggle();
		$('#formulario_digitador').toggle();
 		$('#btn_guardar').toggle();
	}

	$('#btn_buscar').click(function (event) {
		event.preventDefault();
		$('form#frm_buscar')
			.attr('action', $('form#frm_buscar').attr('action')+'/'+$('#id_hoja').val())
			.submit();
	});

	$('#btn_guardar').click(function (event) {
		event.preventDefault();
		$('form#frm_inventario input[name="auditor"]').val($('#id_auditor').val());
		$('form#frm_inventario').submit();
	});

	$('#agr_filtrar').bind('keypress', function (event) {
		if(event.keyCode === 13) {
			event.preventDefault();
			actualizaMateriales($('#agr_filtrar').val());
		}
	});

	$('#agr_filtrar').blur(function () {
		actualizaMateriales($('#agr_filtrar').val());
		$('#agr_material').focus();
	});

	function actualizaMateriales(filtro) {
		var url_datos = js_base_url + 'inventario/ajax-catalogo/' + filtro;
		$.get(url_datos, function (data) {$('#agr_material').html(data); });
	}

});
