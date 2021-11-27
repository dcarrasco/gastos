@props(['movimiento'])

<tr class="hover:{{ themeColor('tr_hover') }}">
    <td class="py-2 text-center">{{ optional($movimiento->fecha)->format('d-m-Y') }}</td>
    <td>{{ $movimiento->glosa }}</td>
    <td>{{ $movimiento->serie }}</td>
    <td>{{ $movimiento->tipoGasto->tipo_gasto }}</td>
    <td class="text-right">
        {{ fmtMonto($movimiento->monto) }}
        <x-signo-movimiento :signo="optional($movimiento->tipoMovimiento)->signo" />
    </td>
    <td class="text-right">{{ fmtMonto($movimiento->saldo_final) }}</td>
    <td>
        @can('delete', $movimiento)
            <a class="inline-block hover:text-blue-500 align-text-top py-0 cursor-pointer"
                x-on:click="openDeleteModal = true;
                    deleteMessage = '{!! $movimiento->deleteMessage() !!}';
                    urlDelete = '{!! route('gastos.borrarGasto', [$movimiento->getKey()]) !!}'"
            >
                <x-heroicon.delete width="14" height="14" class="mb-1 py-0"/>
            </a>
        @endcan
    </td>
</tr>
