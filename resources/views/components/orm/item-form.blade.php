<div class="grid grid-cols-3">
    <div class="col-span-1 px-5 py-4 font-bold {{ $errors->has($field->getModelAttribute($resource)) ? 'text-red-500' : '' }}">
        {{ $field->getName() }}
        @if ($field->isRequired())
            <span class="text-red-500">*</span>
        @endif
    </div>

    <div class="col-span-2 px-5 py-4">
        {{ $field->formItem() }}

        @if ($errors->has($field->getModelAttribute($resource)))
            <div class="text-red-500">{!! $errors->first($field->getModelAttribute($resource)) !!}</div>
        @endif
    </div>
</div>
