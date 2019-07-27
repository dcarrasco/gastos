<div class="row border-top bg-light rounded-bottom-lg">
    <div class="col-md-3 py-3 px-4">
        <h6 class="mb-0 font-weight-bold">
            <strong>
            @if($paginator->onFirstPage())
                <span class="text-black-40" style="cursor: not-allowed !important;">Anterior</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">Anterior</a>
            @endif
            </strong>
        </h6>
    </div>

    <div class="col-md-6 py-3 px-4 text-black-40 text-center">
        <h6 class="mb-0">
            {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} /
            {{ $paginator->total() }}
        </h6>
    </div>

    <div class="col-md-3 text-right py-3 px-4">
        <h6 class="mb-0 font-weight-bold">
            <strong>
            @if($paginator->currentPage() == $paginator->lastPage())
                <span class="text-black-40" style="cursor: not-allowed !important;">Siguiente</span>
            @else
                <a href="{{ $paginator->nextPageUrl() }}">Siguiente</a>
            @endif
            </strong>
        </h6>
    </div>
</div>
