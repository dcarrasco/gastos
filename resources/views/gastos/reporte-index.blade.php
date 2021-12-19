<x-layout.app>

    <x-gastos.reporte.form-filter :cuentas="$cuentas" :tiposMovimientos="$tiposMovimientos" />

    @can('view-any', 'App\Models\Gastos\Gasto')
        <x-gastos.reporte.tabla-reporte :reporte="$reporte" :cuentas="$cuentas" />
    @endcan

</x-layout.app>
