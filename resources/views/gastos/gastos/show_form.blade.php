<form>
    <div class="form-row">
        <div class="col-3">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-2">
            <label class="col-form-label">A&ntilde;o</label>
        </div>
        <div class="col-3">
            <label class="col-form-label">Mes</label>
        </div>
    </div>

    <div class="form-row">
        <div class="col-3">
            {{ Form::select('cuenta_id', $selectCuentas, request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-2">
            {{ Form::selectYear('anno', $today->year, 2015, request('anno', $today->year), ['class' => 'form-control']) }}
        </div>
        <div class="col-3">
            {{ Form::selectMonth('mes', request('mes', $today->month), ['class' => 'form-control']) }}
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary">Consultar</button>
            <button name="recalcula" value="recalcula" class="btn btn-secondary pull-right">Recalcula saldos</button>
        </div>
    </div>
</form>
