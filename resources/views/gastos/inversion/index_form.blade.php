<form>
    <div class="form-row">
        <div class="offset-md-3 col-md-3">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">A&ntilde;o</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-3 col-md-3">
            <x-form-input name="cuenta_id" type="select" :options=$cuentas />
        </div>
        <div class="col-md-2">
            <x-form-input name="anno" type="selectYear" :default="$today->year" :from-year="$today->year" to-year="2015" />
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>
