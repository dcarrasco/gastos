@props(['selectCuentas', 'selectTiposGastos'])

<tr class="hover:bg-blue-100">
    <form method="POST">
        @csrf
        <input type="hidden" name="cuenta_id" value="{{ request('cuenta_id', $selectCuentas->keys()->first()) }}">
        <input type="hidden" name="anno" value="{{ request('anno', today()->year) }}">
        <input type="hidden" name="mes" value="{{ request('mes', today()->month) }}">
        <td class="px-1 py-2">
            <x-form-input name="fecha" type="date" class="w-40"/>
        </td>
        <td class="px-1 py-2">
            <x-form-input name="glosa" class="w-full" />
        </td>
        <td class="px-1 py-2">
            <x-form-input name="serie" class="w-32" />
        </td>
        <td class="px-1 py-2">
            <x-form-input name="tipo_gasto_id" type="select" class="w-56" :options=$selectTiposGastos placeholder="&mdash;" />
        </td>
        <td class="px-1 py-2">
            <x-form-input name="monto" class="w-24" />
        </td>
        <td class="px-1 py-2">
            <x-button type="submit" name="submit">
                Ingresar
            </x-button>
        </td>
        <td></td>
    </form>
</tr>
