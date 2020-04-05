<div class="form-row">
    <div class="offset-md-3 col-md-2">
        <label class="col-form-label">Cuenta</label>
    </div>
    <div class="col-md-2">
        <label class="col-form-label">A&ntilde;o</label>
    </div>
    <div class="col-md-2">
        <label class="col-form-label">Mes</label>
    </div>
</div>

<div class="form-row">
    <div class="offset-md-3 col-md-2">
        <x-form-input name="cuenta_id" type="select" :options=$formCuenta />
    </div>
    <div class="col-md-2">
        <x-form-input name="anno" type="selectYear" :default="$today->year" :from-year="$today->year" to-year="2015" />
    </div>
    <div class="col-md-2">
        <x-form-input name="mes" type="selectMonth" :default="$today->month" />
    </div>
</div>

<div class="form-row">
    <div class="offset-md-1 col-md-2">
        <label class="col-form-label">Datos</label>
    </div>
</div>

<div class="form-row">
    <div class="offset-md-1 col-md-10">
        {{ Form::textarea('datos', request('datos'), ['class' => 'form-control']) }}
        <div class="text-right my-3">
            <button type="submit" class="btn btn-primary text-right">Procesar</button>
        </div>
    </div>
</div>
