<form>
    <div class="form-row">
        <div class="offset-2 col-2">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-2">
            <label class="col-form-label">A&ntilde;o</label>
        </div>
        <div class="col-2">
            <label class="col-form-label">Tipo Movimiento</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-2 col-2">
            <x-form-input name="cuenta_id" type="select" :options=$cuentas />
        </div>
        <div class="col-2">
            <x-form-input name="anno" type="selectYear" :from-year="$today->year" to-year="2015" />
        </div>
        <div class="col-2">
            <x-form-input name="tipo_movimiento_id" type="select" :options=$tiposMovimientos />
        </div>
        <div class="col-2">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>
