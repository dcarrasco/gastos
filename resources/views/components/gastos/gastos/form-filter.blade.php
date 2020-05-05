<div class="row">
    <div class="col-9">
        <form id="filtroReporte" method="GET" class="form-inline">
            <label class="mr-2">Cuenta</label>
            <x-form-input name="cuenta_id" type="select" :options=$selectCuentas />

            <label class="ml-5 mr-2">A&ntilde;o</label>
            <x-form-input name="anno" type="selectYear" :default="today()->year" :from-year="today()->year" to-year="2015" />

            <label class="ml-5 mr-2">Mes</label>
            <x-form-input name="mes" type="selectMonth" :default="today()->month" />
        </form>
    </div>

    <div class="col-3">
        <button name="recalcula" value="recalcula" class="btn btn-light border pull-right">Recalcula saldos</button>
    </div>
</div>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>

