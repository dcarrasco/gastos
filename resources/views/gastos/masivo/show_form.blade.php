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
        {{ Form::select('cuenta_id', $formCuenta, request('cuenta_id'), ['class' => 'form-control']) }}
    </div>
    <div class="col-md-2">
        {{ Form::selectYear('anno', $today->year, 2015, request('anno', $today->year), ['class' => 'form-control']) }}
    </div>
    <div class="col-md-2">
        {{ Form::selectMonth('mes', request('mes', $today->month), ['class' => 'form-control']) }}
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
