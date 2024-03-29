<x-layout.app>
    <form method="POST" id="form-masivo" x-data x-ref="form">
    @csrf
    <x-gastos.masivo.form :formCuenta="$formCuenta" :formParser="$formParser" />

    @if (count($datosMasivos))

        <x-gastos.masivo.table :datosMasivos="$datosMasivos" :selectTiposGastos="$selectTiposGastos" />

        @if ($agregarDatosMasivos)
            @can('create', 'App\Models\Gastos\Gasto')
                <div class="flex justify-end py-4">
                    <x-button type="submit" name="agregar" value="agregar"
                        x-on:click.prevent="$refs.form.action='{{ route('gastos.ingresoMasivoAdd') }}'; $refs.form.submit();"
                    >
                        Agregar
                    </x-button>
                </div>
            @endcan
        @endif
    @endif

    </form>
</x-layout.app>
