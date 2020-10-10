<div class="flex items-center justify-between px-3 py-3 text-gray-500">
    <div class="font-bold">
        @if($resource->getPaginator()->onFirstPage())
            <span class="" style="cursor: not-allowed !important;">Anterior</span>
        @else
            <a href="{{ $resource->getPaginator()->previousPageUrl() }}" class="hover:text-blue-500">Anterior</a>
        @endif
    </div>

    <div class="">
        {{ $resource->getPaginator()->firstItem() }} - {{ $resource->getPaginator()->lastItem() }} /
        {{ $resource->getPaginator()->total() }}
    </div>

    <div class="font-bold">
        @if($resource->getPaginator()->currentPage() == $resource->getPaginator()->lastPage())
            <span class="" style="cursor: not-allowed !important;">Siguiente</span>
        @else
            <a href="{{ $resource->getPaginator()->nextPageUrl() }}" class="hover:text-blue-500">Siguiente</a>
        @endif
    </div>
</div>
