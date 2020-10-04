<form id="filtroReporte" method="GET" class="flex justify-center items-center pb-6">
    <div class="px-4">Cuenta</div>
    <x-form-input name="cuenta_id" type="select" :options=$cuentas class="" />

    <div class="ml-5 px-4">A&ntilde;o</div>
    <x-form-input name="anno" type="selectYear" :from-year="today()->year" to-year="2015" />

    <div class="ml-5 px-4">Tipo Movimiento</div>
    <x-form-input name="tipo_movimiento_id" type="select" :options=$tiposMovimientos />
</form>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>
