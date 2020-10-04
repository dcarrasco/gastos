<div class="grid grid-cols-4 px-5 py-5 {{ $errors->has($field->getModelAttribute($resource)) ? '-mx-5 bg-red-100' : '' }}">
    <div class="col-span-1 {{ $errors->has($field->getModelAttribute($resource)) ? 'text-red-500' : 'text-gray-600' }}">
        {{ $field->getName() }}

        @if ($field->isRequired())
            <span class="text-red-500">*</span>
        @endif
    </div>

    <div class="col-span-2">
        {{ $field->formItem() }}

        @if ($errors->has($field->getModelAttribute($resource)))
            <div class="text-red-500">{!! $errors->first($field->getModelAttribute($resource)) !!}</div>
        @endif
    </div>
</div>
