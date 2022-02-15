@props(['field', 'resource'])

<div class="grid grid-cols-4 px-5 py-5 text-gray-600">
    <div class="col-span-1 @error($field->getModelAttribute($resource)) text-red-700 @enderror">
        {{ $field->getName() }}

        @if ($field->isRequired())
            <span class="text-red-700">*</span>
        @endif
    </div>

    <div class="col-span-2">
        {{ $field->formItem() }}

        @error($field->getModelAttribute($resource))
            <div class="text-red-700 text-sm font-bold">{!! $message !!}</div>
        @enderror
    </div>
</div>
