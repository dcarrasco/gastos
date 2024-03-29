<x-layout.app :resource=$resource :accion="'Detalle '.$resource->getLabel().': '.$resource->title()">
    <x-orm.title>
        {{ trans('orm.title_show') }}
        {!! $resource->getLabel() !!}

        <div class="flex justify-end" x-data="{openDeleteModal: false, urlDelete: '', deleteMessage: ''}">

            @can('delete', $resource->model())
                <a
                    href="#"
                    class="bg-white hover:bg-gray-200 mx-2 px-4 py-2 rounded-md border focus:outline-none focus:shadow-outline"
                    x-on:click.prevent="
                        openDeleteModal=true;
                        deleteMessage='{!! $resource->deleteMessage() !!}',
                        urlDelete='{!! route($routeName.'.destroy', $resource->getRouteControllerId()) !!}'
                    "
                >
                    <x-heroicon.delete width="20" height="20"/>
                </a>
            @endcan

            @can('update', $resource->model())
                <a
                    href="{{ route($routeName.'.edit', $resource->getRouteControllerId()) }}"
                    class="bg-blue-500 text-gray-100 hover:bg-blue-700 px-4 py-2 rounded-md border focus:outline-none focus:shadow-outline"
                >
                    <x-heroicon.edit width="20" height="20" />
                </a>
            @endcan

            <x-orm.list.modal-delete :resource="$resource" />
        </div>
    </x-orm.title>

    <!-- -----------------------------  FIELDS  ---------------------------- -->
    <x-orm.field-panel>
        @foreach($resource->getFields() as $field)
            <x-orm.item-show :field="$field" />
        @endforeach
    </x-orm.field-panel>

</x-layout.app>
