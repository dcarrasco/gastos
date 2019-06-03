@if ($paginationLinks != '')
<div class="row border-top bg-light rounded-bottom-lg">
    <div class="col-9 pl-0">
        {{ $paginationLinks }}
    </div>

    <div class="col-3 text-nowrap text-right text-secondary py-2">
        {{ ($modelList->currentPage() - 1)*$modelList->perPage() + 1 }}
        -
        {{ $modelList->currentPage() * $modelList->perPage() > $modelList->total() ? $modelList->total() : $modelList->currentPage() * $modelList->perPage() }}
        de
        {{ $modelList->total() }}
    </div>
</div>
@endif
