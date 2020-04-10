<div class="form-row">
    <div class="offset-3 col-2">
        <label class="col-form-label">Cuenta</label>
    </div>
    <div class="col-2">
        <label class="col-form-label">A&ntilde;o</label>
    </div>
    <div class="col-2">
        <label class="col-form-label">Mes</label>
    </div>
</div>

<div class="form-row">
    <div class="offset-3 col-2">
        <x-form-input name="cuenta_id" type="select" :options=$formCuenta />
    </div>
    <div class="col-2">
        <x-form-input name="anno" type="selectYear" :default="$today->year" :from-year="$today->year" to-year="2015" />
    </div>
    <div class="col-2">
        <x-form-input name="mes" type="selectMonth" :default="$today->month" />
    </div>
</div>

<div class="form-row">
    <div class="offset-1 col-2">
        <label class="col-form-label">Datos</label>
    </div>
</div>

<div class="form-row">
    <div class="offset-1 col-10">
        <textarea name="datos" class="form-control" cols="50" rows="10">
            {{ request('datos') }}
        </textarea>
        <div class="text-right my-3">
            <button type="submit" class="btn btn-primary text-right">Procesar</button>
        </div>
    </div>
</div>
