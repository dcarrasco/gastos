<x-layout.app>

    <div class="row">
        <div class="col-10">
            <h4>
                {{ trans('orm.title_show') }}
                {!! $resource->getLabel() !!}
            </h4>
        </div>

        <div class="col-2 my-2 text-right">
            <a href="" class="btn btn-light border py-1 shadow-sm" data-toggle="modal" data-target="#modalBorrar">
                <x-heroicon.delete width="20" height="20"/>
            </a>
            <a href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}" class="btn btn-primary border py-1 shadow-sm">
                <x-heroicon.edit width="20" height="20" style="fill: #EEE"/>
            </a>
        </div>
    </div>

    <!-- -----------------------------  FIELDS  ---------------------------- -->
    <div class="container mt-2 border rounded-lg bg-white shadow-sm">
        @foreach($resource->getFields() as $field)
            <x-orm.item-show :field=$field />
        @endforeach
    </div>

    <x-orm.list.modal-delete :resource="$resource" />

</x-layout.app>
