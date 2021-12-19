<x-layout.app>

    <x-gastos.reporte.form-filter-gastos-totales />

    @can('view-any', 'App\Models\Gastos\Gasto')
        <x-gastos.reporte.tabla-reporte-total-gastos :reporte="$reporte" />
    @endcan

</x-layout.app>
