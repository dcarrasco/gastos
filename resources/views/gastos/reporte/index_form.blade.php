<form id="filtroReporte" method="GET" class="form-inline justify-content-center">
    <label class="mr-2">Cuenta</label>
    <x-form-input name="cuenta_id" type="select" :options=$cuentas class="" />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input name="anno" type="selectYear" :from-year="$today->year" to-year="2015" />

    <label class="ml-5 mr-2">Tipo Movimiento</label>
    <x-form-input name="tipo_movimiento_id" type="select" :options=$tiposMovimientos />
</form>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>
