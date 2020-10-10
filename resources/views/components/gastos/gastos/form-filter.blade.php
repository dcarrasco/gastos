<div class="flex justify-between pb-6">
    <form id="filtroReporte" method="GET" class="col-span-3 flex justify-center items-center">
        <label class="mr-2">Cuenta</label>
        <x-form-input name="cuenta_id" type="select" :options=$selectCuentas />

        <label class="ml-5 mr-2">A&ntilde;o</label>
        <x-form-input name="anno" type="selectYear" :default="today()->year" :from-year="today()->year" to-year="2015" />

        <label class="ml-5 mr-2">Mes</label>
        <x-form-input name="mes" type="selectMonth" :default="today()->month" />
    </form>

    <div class="col-span-1">
        <button name="recalcula" value="recalcula" class="bg-gray-300 hover:bg-gray-400 font-bold border rounded-md p-2 focus:outline-none focus:shadow-outline">Recalcula saldos</button>
    </div>
</div>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>

