<div class="flex items-center justify-between border-t px-3 py-3 text-gray-500 bg-blue-100 rounded-b-lg">
    <div class="font-bold">
        @if($resource->getPaginator()->onFirstPage())
            <span class="cursor-not-allowed">Anterior</span>
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
            <span class="cursor-not-allowed">Siguiente</span>
        @else
            <a href="{{ $resource->getPaginator()->nextPageUrl() }}" class="hover:text-blue-500">Siguiente</a>
        @endif
    </div>
</div>
