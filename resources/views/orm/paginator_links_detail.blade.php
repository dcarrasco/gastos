<div class="row border-top bg-light rounded-bottom-lg">
    <div class="col-md-9 pl-0">
        {{ $resource->getPaginator()->appends(request()->all())->links() }}
    </div>

    <div class="col-md-3 text-nowrap text-right text-secondary py-2">
        {{ $resource->getPaginator()->firstItem() }} - {{ $resource->getPaginator()->lastItem() }} de {{ $resource->getPaginator()->total() }}
    </div>
</div>
