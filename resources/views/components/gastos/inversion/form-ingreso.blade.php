@props(['cuentas', 'tiposMovimientos'])

<tr>
    <form method="POST">
        @csrf
        <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $cuentas->keys()->first()) }}">
        <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
        <td></td>
        <td></td>
        <td class="py-2"><x-form-input type="date" name="fecha" /></td>
        <td><x-form-input name="glosa" class="w-32" /></td>
        <td><x-form-input type="select" name="tipo_movimiento_id" :options="$tiposMovimientos" /></td>
        <td><x-form-input name="monto" class="w-32" /></td>
        <td>
            <x-button type="submit">
                Ingresar
            </x-button>
        </td>
        <td></td>
        <td></td>
        <td></td>
    </form>
</tr>
