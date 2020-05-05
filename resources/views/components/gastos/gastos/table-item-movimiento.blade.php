<tr>
    <td>{{ $movimiento->anno }}</td>
    <td>{{ $movimiento->mes }}</td>
    <td>{{ optional($movimiento->fecha)->format('d-m-Y') }}</td>
    <td>{{ $movimiento->glosa }}</td>
    <td>{{ $movimiento->serie }}</td>
    <td>{{ $movimiento->tipoGasto->tipo_gasto }}</td>
    <td class="text-right">
        {{ fmtMonto($movimiento->monto) }}
        <x-signo-movimiento :movimiento=$movimiento />
    </td>
    <td class="text-right">{{ fmtMonto($movimiento->saldo_final) }}</td>
    <td>
        <form method="POST" action="{{ route('gastos.borrarGasto', http_build_query(request()->all())) }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" value="{{ $movimiento->getKey() }}">
            <button type="submit" class="btn btn-sm btn-link py-md-0 by-md-0">
                <x-heroicon.delete width="14" height="14" class="mb-1"/>
            </button>
        </form>
    </td>
</tr>
