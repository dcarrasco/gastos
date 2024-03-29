@props(['resource'])
@php
    $activeSearchClass = ! empty(Request::input($resource->urlSearchKey())) ? 'bg-yellow-100' : '';
@endphp

<form method="GET" class="flex justify-between py-2">
    <div id="{{ $resource->urlSearchKey() }}_group" class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center text-gray-500">
            <x-heroicon.search />
        </div>
        <input
            type="text"
            name="{{ $resource->urlSearchKey() }}"
            value="{{ Request::input($resource->urlSearchKey()) }}"
            class="{{ $activeSearchClass }} pl-10 py-2 rounded-full text-sm shadow outline-none focus:shadow-outline"
            id="{{ $resource->urlSearchKey() }}"
            size="30"
            maxlength="30"
            placeholder="{{ trans('orm.filter') }}"
        >
    </div>

    @can('create', $resource->model())
        <x-button type="link" link="{{ route($routeName.'.create', [$resource->getName()]) }}">
            {{ trans('orm.button_new') }} {{ $resource->getLabel() }}
        </x-button>
    @endcan
</form>
