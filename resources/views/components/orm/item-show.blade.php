@props(['field'])

<div class="grid grid-cols-4 px-5 py-5">
    <div class="col-span-1 text-gray-600">
        {{ $field->getName() }}
    </div>

    <div class="col-span-2">
        {{ $field->getFormattedValue() }}
    </div>
</div>
