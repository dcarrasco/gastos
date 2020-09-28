<tr>
    <form method="POST">
        @csrf
        <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $selectCuentas->keys()->first()) }}">
        <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
        <input type="hidden" name="mes" value="{{ request('mes', today()->month) }}">
        <td></td>
        <td></td>
        <td><x-form-input name="fecha" type="date" class=""/></td>
        <td><x-form-input name="glosa" class="" /></td>
        <td><x-form-input name="serie" class="" /></td>
        <td><x-form-input name="tipo_gasto_id" type="select" class="" :options=$selectTiposGastos /></td>
        <td><x-form-input name="monto" class="" /></td>
        <td><button type="submit" name="submit" class="btn btn-primary btn-sm">Ingresar</button></td>
        <td></td>
    </form>
</tr>
