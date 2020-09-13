<a class="inline-block" href="{{ route($routeName.'.show', [$resource->getName(), $resource->model()->getKey()]) }}">
    <x-heroicon.show />
</a>
<a class="inline-block" href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}">
    <x-heroicon.edit />
</a>
<a class="inline-block"
    data-toggle="modal"
    data-target="#modalBorrar"
    data-url-form="{!! route($routeName.'.destroy', [$resource->getName(), $resource->model()->getKey()]) !!}"
    data-message="{!! trans('orm.delete_confirm', ['model' => $resource->getLabel(), 'item' => $resource->title() ]) !!}"
    id="delete-href"
    style="cursor: pointer;"
>
    <x-heroicon.delete />
</a>
