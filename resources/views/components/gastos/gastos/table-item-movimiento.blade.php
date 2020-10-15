<tr class="hover:bg-blue-100">
    <td class="py-2">{{ $movimiento->anno }}</td>
    <td>{{ $movimiento->mes }}</td>
    <td class="text-center">{{ optional($movimiento->fecha)->format('d-m-Y') }}</td>
    <td>{{ $movimiento->glosa }}</td>
    <td>{{ $movimiento->serie }}</td>
    <td>{{ $movimiento->tipoGasto->tipo_gasto }}</td>
    <td class="text-right">
        {{ fmtMonto($movimiento->monto) }}
        <x-signo-movimiento :movimiento=$movimiento />
    </td>
    <td class="text-right">{{ fmtMonto($movimiento->saldo_final) }}</td>
    <td>
        @can('delete', $movimiento)
        <a class="inline-block hover:text-blue-500 align-text-top py-0"
            @click="openDeleteModal=true"
            x-on:click="deleteMessage='<p>Eliminar movimiento:</p><p>{{ optional($movimiento->fecha)->format('d-m-Y') }} {{ $movimiento->glosa}} {{ fmtMonto($movimiento->monto) }}</p>', urlDelete='{!! route('gastos.borrarGasto', [$movimiento->getKey()]) !!}'"
            style="cursor: pointer;"
        >
            <x-heroicon.delete width="14" height="14" class="mb-1 py-0"/>
        </a>
        @endcan
    </td>
</tr>
