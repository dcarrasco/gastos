@props([
    'resource' => null,
])

<x-modal>
    <x-slot name="title">
        <h5 class="text-xl font-bold">
            Borrar {{ optional($resource)->getLabel() }}
        </h5>
    </x-slot>

    <x-slot name="buttons">
        <x-button color="secondary" class="mx-2" x-on:click="openDeleteModal=false">
            {{ trans('orm.button_cancel') }}
        </x-button>
        <form method="POST" x-bind:action="urlDelete" action="">
            @csrf
            @method('DELETE')
            <x-button type="submit" color="danger">
                <span class="fa fa-trash-o"></span>
                {{ trans('orm.button_delete') }}
            </x-button>
        </form>
    </x-slot>

    <div x-html="deleteMessage"></div>
</x-modal>
