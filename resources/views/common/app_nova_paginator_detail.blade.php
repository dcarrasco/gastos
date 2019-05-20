@if ($paginationLinks != '')
<tfoot>
    <tr class="table-secondary">
        <td class="py-md-0 px-md-0" colspan="{{ collect($resource->indexFields(request()))->count() }}">
            {{ $paginationLinks }}
        </td>
        <td class="text-right text-secondary">
            {{ ($modelList->currentPage() - 1)*$modelList->perPage() + 1 }}&nbsp;-&nbsp;{{ $modelList->currentPage() * $modelList->perPage() > $modelList->total() ? $modelList->total() : $modelList->currentPage() * $modelList->perPage() }}&nbsp;de&nbsp;{{ $modelList->total() }}
        </td>
    </tr>
</tfoot>
@endif
