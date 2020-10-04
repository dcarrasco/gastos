<form id="filtroReporte" method="GET" class="flex justify-center items-center pb-6">
    <div class="px-4">Cuenta</div>
    <x-form-input name="cuenta_id" type="select" :options=$cuentas />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input name="anno" type="selectYear" :default="today()->year" :from-year="today()->year" to-year="2015" />
</form>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>
