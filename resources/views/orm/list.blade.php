<x-layout.app>

    <!-- ------------------------- CARDS ------------------------- -->
    <x-orm.cards-container :cards="$cards" />

    <!-- ------------------------- LABEL ------------------------- -->
    <x-orm.title>
        {{ $resource->getLabelPlural() }}
    </x-orm.title>

    <!-- ------------------------- SEARCH & NEW ------------------------- -->
    <x-orm.list.search-and-new :resource="$resource" />

    <!-- ------------------------- LIST DATA ------------------------- -->
    <div class="my-5 shadow-sm rounded-lg border divide-y divide-gray-400">
        <x-orm.list.filters :resource="$resource" />

        @if ($resource->resourceList()->count() == 0)
            <x-orm.list.no-items />
        @else
            <x-orm.list.table :resource="$resource" />
        @endif
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('a#delete-href').click(function(e) {
                e.preventDefault();
                $('#formDelete').attr('action', $(this).data('url-form'));
                $('#delete-message').html($(this).data('message'));
            });

            if ($('#{{ $resource->urlSearchKey() }}').val() != '') {
                $('#{{ $resource->urlSearchKey() }}').addClass('search-found');
                $('#{{ $resource->urlSearchKey() }}_group').addClass('search-found');
                $('#{{ $resource->urlSearchKey() }}_group').removeClass('bg-white');
            }
        });
    </script>

</x-layout.app>
