<a class="inline-block hover:text-blue-500" href="{{ route($routeName.'.show', [$resource->getName(), $resource->model()->getKey()]) }}">
    <x-heroicon.show />
</a>
<a class="inline-block hover:text-blue-500" href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}">
    <x-heroicon.edit />
</a>
<a class="inline-block hover:text-blue-500"
    @click="openDeleteModal=true"
    x-on:click="deleteMessage='{!! trans('orm.delete_confirm', ['model' => $resource->getLabel(), 'item' => $resource->title() ]) !!}', urlDelete='{!! route($routeName.'.destroy', [$resource->getName(), $resource->model()->getKey()]) !!}'"
    style="cursor: pointer;"
>
    <x-heroicon.delete />
</a>
