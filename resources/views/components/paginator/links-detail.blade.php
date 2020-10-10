<div class="flex justify-between border-t rounded-b-lg bg-blue-100">
    <div class="">
        {{ $resource->getPaginator()->appends(request()->all())->links() }}
    </div>

    <div class="p-2">
        {{ $resource->getPaginator()->firstItem() }} - {{ $resource->getPaginator()->lastItem() }} de {{ $resource->getPaginator()->total() }}
    </div>
</div>
