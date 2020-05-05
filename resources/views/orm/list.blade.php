<x-layout.app>

    <!-- ------------------------- CARDS ------------------------- -->
    <x-orm.cards-container :cards="$cards" />

    <!-- ------------------------- LABEL ------------------------- -->
    <div class="row">
        <div class="col-12">
            <h4>{{ $resource->getLabelPlural() }}</h4>
        </div>
    </div>

    <!-- ------------------------- SEARCH & NEW ------------------------- -->
    <x-orm.list.search-and-new :resource="$resource" />

    <!-- ------------------------- LIST DATA ------------------------- -->
    <div class="container shadow-sm rounded-lg border">
        <x-orm.list.filters :resource="$resource" />

        @if ($resource->resourceList()->count() == 0)
            <x-orm.list.no-items />
        @else
            <div class="row">
                <div class="col-12 px-0">
                    <x-orm.list.table :resource=$resource />
                </div>
            </div>

            <x-paginator.links :resource="$resource" />

        @endif
    </div> <!-- container -->

    <x-orm.list.modal-delete :resource="$resource" />

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
