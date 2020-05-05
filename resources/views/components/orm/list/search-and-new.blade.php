<form method="GET" class="form-search">
    <div class="row pt-2 mb-3 hidden-print">
        <div class="col-4">
            <div id="{{ $resource->urlSearchKey() }}_group" class="input-group input-group-sm bg-white border rounded">
                <x-heroicon.search />
                <input type="text" name="{{ $resource->urlSearchKey() }}" value="{{ Request::input($resource->urlSearchKey()) }}" class="form-control border-0" id="{{ $resource->urlSearchKey() }}" maxlength="30" placeholder="{{ trans('orm.filter') }}">
            </div>
        </div>

        <div class="col-8 text-right">
            <a href="{{ route($routeName.'.create', [$resource->getName()]) }}" class="btn btn-primary text-right px-3 font-weight-bold text-shadow" id="btn_mostrar_agregar" role="button">
                {{ trans('orm.button_new') }} {{ $resource->getLabel() }}
            </a>
        </div>
    </div>
</form>
