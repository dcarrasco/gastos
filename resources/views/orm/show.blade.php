<x-layout.app>
    <x-orm.title>
        {{ trans('orm.title_show') }}
        {!! $resource->getLabel() !!}
        <div class="flex justify-end">
            <a href="" class="bg-white hover:bg-gray-200 mx-2 px-4 py-2 rounded-md border" data-toggle="modal" data-target="#modalBorrar">
                <x-heroicon.delete width="20" height="20"/>
            </a>
            <a href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}" class="bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded-md border">
                <x-heroicon.edit width="20" height="20" style="fill: #EEE"/>
            </a>
        </div>
    </x-orm.title>

    <!-- -----------------------------  FIELDS  ---------------------------- -->
    <x-orm.field-panel x-data="{openDeleteModal: false}">
        @foreach($resource->getFields() as $field)
            <x-orm.item-show :field=$field />
        @endforeach
        <x-orm.list.modal-delete :resource="$resource" />
    </x-orm.field-panel>

</x-layout.app>
