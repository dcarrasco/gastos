<div class="row border-top bg-light rounded-bottom-lg">
    <div class="col-9 pl-0">
        {{ $paginationLinks }}
    </div>

    <div class="col-3 text-nowrap text-right text-secondary py-2">
        {{ $modelList->firstItem() }} - {{ $modelList->lastItem() }} de {{ $modelList->total() }}
    </div>
</div>
