@if($resource->paginationLinksDetail())
    <x-paginator.links-detail :resource="$resource"/>
@else
    <x-paginator.links-short :resource="$resource"/>
@endif
