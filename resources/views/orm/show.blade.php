<x-layout.app>
    <x-orm.title>
        {{ trans('orm.title_show') }}
        {!! $resource->getLabel() !!}
    </x-orm.title>

    <div class="flex justify-end">
        <a href="" class="bg-white hover:bg-gray-200 mx-2 p-2 rounded-md border" data-toggle="modal" data-target="#modalBorrar">
            <x-heroicon.delete width="20" height="20"/>
        </a>
        <a href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}" class="bg-blue-500 hover:bg-blue-700 p-2 rounded-md border">
            <x-heroicon.edit width="20" height="20" style="fill: #EEE"/>
        </a>
    </div>

    <!-- -----------------------------  FIELDS  ---------------------------- -->
    <div class="mt-2 border rounded-lg bg-white shadow-sm divide-y divide-gray-300" x-data="{openDeleteModal: false}">
        @foreach($resource->getFields() as $field)
            <x-orm.item-show :field=$field />
        @endforeach
        <x-orm.list.modal-delete :resource="$resource" />
    </div>
</x-layout.app>
