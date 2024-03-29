@props(['datosMasivos', 'selectTiposGastos'])

<div class="py-2 {{ themeColor('thead_bg') }} rounded-lg border shadow-sm">
    <table class="w-full table-auto text-sm">
        <thead class="{{ themeColor('thead_bg') }} border-b-2 border-gray-400 text-gray-600 uppercase">
            <tr>
                <th class="py-2">Fecha</th>
                <th>Glosa</th>
                <th>Serie</th>
                <th>Tipo Gasto</th>
                <th>Monto</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($datosMasivos as $gasto)
                <tr class="hover:bg-blue-100">
                    <td class="py-2">{{ $gasto->fecha->format('d-m-Y') }}</td>
                    <td>{{ $gasto->glosa }}</td>
                    <td>{{ $gasto->serie }}</td>
                    <td>
                        @if ($gasto->tipoGasto)
                            {{ optional($gasto->tipoGasto)->tipo_gasto }}
                        @else
                            <div class="flex flex-nowrap items-center">
                                <x-form-input
                                    type="select"
                                    name="tipo_gasto_id"
                                    class="w-48"
                                    :options="$selectTiposGastos"
                                    placeholder="&mdash;"
                                />
                                &nbsp;
                                <div
                                    class="inline-block cursor-pointer"
                                    x-on:click.prevent="
                                        $refs.glosa_tipo_gasto.value='{{ $gasto->glosa }}';
                                        $refs.form.action='{{ route('gastos.ingresoMasivoAddTipoGasto') }}';
                                        $refs.form.submit();
                                    "
                                >
                                    <x-heroicon.add class="inline-block"/>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ fmtMonto($gasto->monto) }}
                        <x-signo-movimiento :movimiento="$gasto" />
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot class="{{ themeColor('thead_bg') }} font-bold border-t-2 border-gray-400">
            <tr>
                <td class="py-2">Total {{ $datosMasivos->count() }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">{{ fmtMonto($datosMasivos->pluck('monto')->sum()) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<input type="hidden" name="glosa_tipo_gasto" x-ref="glosa_tipo_gasto"/>
