@props(['resource'])

@can('view', $resource->model())
<a class="inline-block hover:text-blue-500" href="{{ route($routeName.'.show', $resource->getRouteControllerId()) }}">
    <x-heroicon.show />
</a>
@endcan

@can('update', $resource->model())
<a class="inline-block hover:text-blue-500" href="{{ route($routeName.'.edit', $resource->getRouteControllerId()) }}">
    <x-heroicon.edit />
</a>
@endcan

@can('delete', $resource->model())
<a
    class="inline-block hover:text-blue-500 cursor-pointer"
    @click="openDeleteModal=true"
    x-on:click="deleteMessage='{!! trans('orm.delete_confirm', ['model' => $resource->getLabel(), 'item' => $resource->title() ]) !!}', urlDelete='{!! route($routeName.'.destroy', $resource->getRouteControllerId()) !!}'"
>
    <x-heroicon.delete />
</a>
@endcan
