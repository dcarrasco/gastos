<div class="form-inline justify-content-center">
    <label class="mr-2">Cuenta</label>
    <x-form-input name="cuenta_id" type="select" :options=$formCuenta />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input name="anno" type="selectYear" :default="$today->year" :from-year="$today->year" to-year="2015" />

    <label class="ml-5 mr-2">Mes</label>
    <x-form-input name="mes" type="selectMonth" :default="$today->month" />
</div>

<div class="form-row">
    <label class="offset-1 col-form-label">Datos</label>
</div>

<div class="form-row">
    <textarea name="datos" class="form-control offset-1 col-10" cols="50" rows="10">{{ request('datos') }}</textarea>
</div>

<div class="form-row">
    <div class="offset-1 col-10 text-right my-3">
        <button type="submit" class="btn btn-primary text-right">Procesar</button>
    </div>
</div>
