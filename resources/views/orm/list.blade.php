<x-layout.app>

    <!-- ------------------------- CARDS ------------------------- -->
    <x-orm.cards-container :cards="$cards" />

    <!-- ------------------------- LABEL ------------------------- -->
    <x-orm.title>
        {{ $resource->getLabelPlural() }}
    </x-orm.title>

    <!-- ------------------------- SEARCH & NEW ------------------------- -->
    <x-orm.list.search-and-new :resource="$resource" />

    <!-- ------------------------- LIST DATA ------------------------- -->
    <div class="my-5 shadow-sm rounded-lg border divide-y divide-gray-200">
        <x-orm.list.filters :resource="$resource" />

        @if ($resource->resourceList()->count() == 0)
            <x-orm.list.no-items />
        @else
            @can('view-any', $resource->model())
            <x-orm.list.table :resource="$resource" />
            @endcan
        @endif
    </div>

</x-layout.app>
