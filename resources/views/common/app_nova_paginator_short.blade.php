<div class="row border-top bg-light rounded-bottom-lg">
    <div class="col-3 py-3 px-4">
        <h6 class="mb-0 font-weight-bold">
            <a href="{{ $paginator->previousPageUrl() }}" class="{{ $paginator->onFirstPage() ? 'text-black-40' : ''}}">Anterior</a>
        </h6>
    </div>

    <div class="col-6 py-3 px-4 text-black-40 text-center">
        <h6 class="mb-0">
            {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} /
            {{ $paginator->total() }}
        </h6>
    </div>

    <div class="col-3 text-right py-3 px-4">
        <h6 class="mb-0 font-weight-bold">
            <a href="{{ $paginator->nextPageUrl() }}" class="{{ $paginator->currentPage() == $paginator->lastPage() ? 'text-black-40' : ''}}">Siguiente</a>
        </h6>
    </div>
</div>
