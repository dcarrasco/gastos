<tr>
    <form method="POST">
        @csrf
        <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $selectCuentas->keys()->first()) }}">
        <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
        <input type="hidden" name="mes" value="{{ request('mes', today()->month) }}">
        <td></td>
        <td></td>
        <td class="px-1 py-2"><x-form-input name="fecha" type="date" class="w-40"/></td>
        <td class="px-1 py-2"><x-form-input name="glosa" class="" /></td>
        <td class="px-1 py-2"><x-form-input name="serie" class="w-32" /></td>
        <td class="px-1 py-2"><x-form-input name="tipo_gasto_id" type="select" class="w-40" :options=$selectTiposGastos /></td>
        <td class="px-1 py-2"><x-form-input name="monto" class="w-24" /></td>
        <td class="px-1 py-2"><button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Ingresar</button></td>
        <td></td>
    </form>
</tr>
