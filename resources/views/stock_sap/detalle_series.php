<div class="content-module">

	<div class="content-module-main">
		{detalle_series}
	</div> <!-- fin content-module-main -->

	<div class="content-module-footer cf">

	</div> <!-- fin content-module-footer -->

<?= form_open('stock_analisis_series/historia'); ?>
<?= form_hidden('series'); ?>
<?= form_hidden('show_mov', 'show'); ?>
<?= form_close(); ?>

<script>
$(document).ready(function () {
    $('span.serie').css('cursor', 'pointer');

	$('span.serie').click(function (event) {
		var serie = $(this).text();
		$('input[name="series"]').val(serie);

		$('form').submit();
	});



});
</script>

</div> <!-- fin content-module -->
