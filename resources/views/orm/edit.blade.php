<x-layout.app
    :resource="$resource"
    :accion="trans('orm.title_edit').' '.$resource->getLabel().': '.$resource->title()"
>

    <x-orm.title>
        {{ trans('orm.title_edit') }}
        {!! $resource->getLabel() !!}
    </x-orm.title>

    <form
        method="POST"
        action='{{ route("{$routeName}.update", $resource->getRouteControllerId()) }}'
        x-data="{}"
        x-ref="form"
    >
        @csrf
        @method('PUT')

        <x-orm.field-panel>
            <!-- -----------------------------  FIELDS  ---------------------------- -->
            @foreach($resource->getFields() as $field)
                <x-orm.item-form :field="$field" :resource="$resource" />
            @endforeach

            <!-- -----------------------------  BOTONES  --------------------------- -->
            <x-orm.panel-bottom-buttons>
                <x-button class="mx-2" x-on:click="$refs.redirect.value='same'; $refs.form.submit();">
                    {{ trans('orm.button_update_continue') }}
                </x-button>

                <x-button class="mx-2" type="submit">
                    {{ trans('orm.button_update') }} {{ $resource->getLabel() }}
                </x-button>
            </x-orm.panel-bottom-buttons>
        </x-orm.field-panel>

        <input type="hidden" name="redirect_to" value="next" x-ref="redirect">
    </form>

</x-layout.app>
