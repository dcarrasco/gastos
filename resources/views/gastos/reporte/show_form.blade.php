<form>
    <div class="form-row">
        <div class="offset-md-2 col-md-2">
            <label class="col-form-label">Cuenta</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">A&ntilde;o</label>
        </div>
        <div class="col-md-2">
            <label class="col-form-label">Tipo Movimiento</label>
        </div>
    </div>

    <div class="form-row">
        <div class="offset-md-2 col-md-2">
            {{ Form::select('cuenta_id', $cuentas, request('cuenta_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::selectYear('anno', $today->year, 2015, request('anno'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            {{ Form::select('tipo_movimiento_id', $tiposMovimientos, request('tipo_movimiento_id'), ['class' => 'form-control']) }}
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </div>
</form>
