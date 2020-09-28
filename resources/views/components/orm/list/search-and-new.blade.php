<form method="GET" class="flex justify-between py-2">
    <div id="{{ $resource->urlSearchKey() }}_group" class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center">
            <x-heroicon.search />
        </div>
        <input type="text" name="{{ $resource->urlSearchKey() }}" value="{{ Request::input($resource->urlSearchKey()) }}" class="pl-10 py-2 rounded-md" id="{{ $resource->urlSearchKey() }}" maxlength="30" placeholder="{{ trans('orm.filter') }}">
    </div>

    <a href="{{ route($routeName.'.create', [$resource->getName()]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-md" id="btn_mostrar_agregar" role="button">
        {{ trans('orm.button_new') }} {{ $resource->getLabel() }}
    </a>
</form>
