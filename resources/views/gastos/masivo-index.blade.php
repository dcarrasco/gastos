<x-layout.app>

    <form method="POST" id="form-masivo">
        @csrf

        <x-gastos.masivo.form :formCuenta="$formCuenta" />

        @if (count($datosMasivos))
            <div class="grid grid-cols-10">
                <div class="col-start-2 col-span-8">
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-gray-300 border-b-2 border-gray-400">
                            <tr>
                                <th class="py-2">Fecha</th>
                                <th>Glosa</th>
                                <th>Serie</th>
                                <th>Tipo Gasto</th>
                                <th class="text-right">Monto</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($datosMasivos as $gasto)
                                <tr class="hover:bg-blue-100">
                                    <td class="py-2">{{ $gasto->fecha->format('d-m-Y') }}</td>
                                    <td>{{ $gasto->glosa }}</td>
                                    <td>{{ $gasto->serie }}</td>
                                    <td>{{ optional($gasto->tipoGasto)->tipo_gasto }} </td>
                                    <td class="text-right">
                                        {{ fmtMonto($gasto->monto) }}
                                        <x-signo-movimiento :movimiento=$gasto />
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot class="bg-gray-300 font-bold border-t-2 border-gray-400">
                            <tr>
                                <td class="py-2">Total {{ $datosMasivos->count() }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{ fmtMonto($datosMasivos->pluck('monto')->sum()) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    @if ($agregarDatosMasivos)
                        <div class="flex justify-end py-4">
                            <button name="agregar" value="agregar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-md outline-none">Agregar</button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </form>

    <script type="text/javascript">
        $('button[name="agregar"]').click(function(e) {
            e.preventDefault();
            $('#form-masivo').attr('action', '{{ route("gastos.ingresoMasivoAdd") }}');
            $('#form-masivo').submit();
        });
    </script>
</x-layout.app>
