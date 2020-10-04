<x-layout.app>
    <x-orm.title>
        {{ trans('orm.title_add') }}
        {!! $resource->getLabel() !!}
    </x-orm.title>


    <form method="POST" action='{{ route("$routeName.store", [$resource->getName()]) }}' id="frm_editar">
        @csrf

        <x-orm.field-panel>
            <!-- -----------------------------  FIELDS  ---------------------------- -->
            @foreach($resource->getFields() as $field)
                <x-orm.item-form :field=$field :resource=$resource />
            @endforeach

            <!-- -----------------------------  BOTONES  --------------------------- -->
            <x-orm.panel-bottom-buttons>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md mx-2" id="button_continue">
                    {{ trans('orm.button_create_continue') }}
                </button>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md mx-2">
                    {{ trans('orm.button_create') }} {{ $resource->getLabel() }}
                </button>
            </x-orm.panel-bottom-buttons>
        </x-orm.field-panel>

        <input type="hidden" name="redirect_to" value="next">
    </form>

    <script>
        $("#button_continue").click(function(event) {
            event.preventDefault;
            $("#frm_editar input[name='redirect_to']").val('same');
            $("#frm_editar").submit();
        })
    </script>

</x-layout.app>
