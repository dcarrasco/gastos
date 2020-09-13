<form method="GET" class="form-search">
    <div class="flex justify-between py-2">
        <div id="{{ $resource->urlSearchKey() }}_group" class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center">
                <x-heroicon.search />
            </div>
            <input type="text" name="{{ $resource->urlSearchKey() }}" value="{{ Request::input($resource->urlSearchKey()) }}" class="pl-10 py-2 rounded-md" id="{{ $resource->urlSearchKey() }}" maxlength="30" placeholder="{{ trans('orm.filter') }}">
        </div>

        <div class="bg-blue-500 text-white font-bold px-2 flex items-center rounded-md">
            <a href="{{ route($routeName.'.create', [$resource->getName()]) }}" class="btn btn-primary text-right px-3 font-weight-bold text-shadow" id="btn_mostrar_agregar" role="button">
                {{ trans('orm.button_new') }} {{ $resource->getLabel() }}
            </a>
        </div>
    </div>
</form>
