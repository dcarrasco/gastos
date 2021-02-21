@props(['field', 'resource'])

<div class="grid grid-cols-4 px-5 py-5 text-gray-600">
    <div class="col-span-1 {{ $errors->has($field->getModelAttribute($resource)) ? 'text-red-700' : '' }}">
        {{ $field->getName() }}

        @if ($field->isRequired())
            <span class="text-red-700">*</span>
        @endif
    </div>

    <div class="col-span-2">
        {{ $field->formItem() }}

        @if ($errors->has($field->getModelAttribute($resource)))
            <div class="text-red-700 text-sm font-bold">{!! $errors->first($field->getModelAttribute($resource)) !!}</div>
        @endif
    </div>
</div>
