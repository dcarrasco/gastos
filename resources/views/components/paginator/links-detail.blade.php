<div class="flex justify-between border-t rounded-b-lg {{ themeColor('tr_hover') }}">
    <div class="">
        {{ $resource->getPaginator()->appends(request()->all())->links() }}
    </div>

    <div class="p-2">
        {{ $resource->getPaginator()->firstItem() }} - {{ $resource->getPaginator()->lastItem() }} de {{ $resource->getPaginator()->total() }}
    </div>
</div>
