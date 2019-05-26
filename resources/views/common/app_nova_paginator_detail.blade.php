@if ($paginationLinks != '')
<div class="bg-light col-md-12 rounded-bottom-lg">
    <div class="row">
        <div class="col-md-9 pl-md-0">
            {{ $paginationLinks }}
        </div>
        <div class="col-md-3 text-nowrap text-right text-secondary py-md-2">
            {{ ($modelList->currentPage() - 1)*$modelList->perPage() + 1 }}
            -
            {{ $modelList->currentPage() * $modelList->perPage() > $modelList->total() ? $modelList->total() : $modelList->currentPage() * $modelList->perPage() }}
            de
            {{ $modelList->total() }}
        </div>
    </div>
</div>
@endif
