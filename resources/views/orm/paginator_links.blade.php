@includeWhen($paginationLinks['detail'], 'orm.paginator_links_detail', $paginationLinks)
@includeWhen(! $paginationLinks['detail'], 'orm.paginator_links_short', $paginationLinks)
