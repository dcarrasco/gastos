@props(['inversion', 'saldo'])

<tr class="hover:bg-blue-100">
    <td class="text-center py-2">{{ $inversion->anno }}</td>
    <td class="text-center">{{ $inversion->mes }}</td>
    <td class="text-center">{{ optional($inversion->fecha)->format('d-m-Y') }}</td>
    <td>{{ $inversion->glosa }}</td>
    <td class="text-center">{{ $inversion->tipoMovimiento->tipo_movimiento }}</td>
    <td class="text-right">
        {{ fmtMonto($inversion->monto) }}
        <x-signo-movimiento :signo="optional($inversion->tipoMovimiento)->signo" />
    </td>
    <td class="text-right">{{ fmtMonto($saldo) }}</td>
    <td>
        @can('delete', $inversion)
            <a class="inline-block hover:text-blue-500 align-text-top py-0 cursor-pointer"
                x-on:click="openDeleteModal = true;
                    deleteMessage = '{!! $inversion->deleteMessage() !!}';
                    urlDelete='{!! route('gastos.borrarInversion', [$inversion->getKey()]) !!}'"
            >
                <x-heroicon.delete width="14" height="14" class="mb-1 py-0"/>
            </a>
        @endcan
    </td>
    <td></td>
    <td></td>
</tr>
