<x-layout.app>
    <x-orm.title>
        {{ trans('orm.title_show') }}
        {!! $resource->getLabel() !!}
        <div class="flex justify-end" x-data="{openDeleteModal: false, urlDelete: '', deleteMessage: ''}">

            @can('delete', $resource->model())
            <a href="#"
                class="bg-white hover:bg-gray-200 mx-2 px-4 py-2 rounded-md border focus:outline-none focus:shadow-outline"
                @click="openDeleteModal=true"
                x-on:click.prevent="deleteMessage='{!! trans('orm.delete_confirm', ['model' => $resource->getLabel(), 'item' => $resource->title() ]) !!}', urlDelete='{!! route($routeName.'.destroy', [$resource->getName(), $resource->model()->getKey()]) !!}'"
            >
                <x-heroicon.delete width="20" height="20"/>
            </a>
            @endcan

            @can('update', $resource->model())
            <a
                href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}"
                class="bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded-md border focus:outline-none focus:shadow-outline"
            >
                <x-heroicon.edit width="20" height="20" style="fill: #EEE"/>
            </a>
            @endcan
            <x-orm.list.modal-delete :resource="$resource" />
        </div>
    </x-orm.title>

    <!-- -----------------------------  FIELDS  ---------------------------- -->
    <x-orm.field-panel>
        @foreach($resource->getFields() as $field)
            <x-orm.item-show :field=$field />
        @endforeach
    </x-orm.field-panel>

</x-layout.app>
