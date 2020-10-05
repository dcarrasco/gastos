<div class="flex justify-between border-top rounded-bottom-lg">
    <div class="">
        {{ $resource->getPaginator()->appends(request()->all())->links() }}
    </div>

    <div class="p-2">
        {{ $resource->getPaginator()->firstItem() }} - {{ $resource->getPaginator()->lastItem() }} de {{ $resource->getPaginator()->total() }}
    </div>
</div>
