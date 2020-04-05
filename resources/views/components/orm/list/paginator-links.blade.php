@if($resource->paginationLinksDetail())
    <x-orm.list.paginator-links-detail :resource="$resource"/>
@else
    <x-orm.list.paginator-links-short :resource="$resource"/>
@endif
