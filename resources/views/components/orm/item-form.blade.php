<div class="grid grid-cols-3">
    <div class="col-span-1 px-5 py-4">
        <h6 class="font-bold {{ $errors->has($field->getModelAttribute($resource)) ? 'text-danger' : 'text-muted' }}"> {{ $field->getName() }} </h6>
        @if ($field->isRequired())
            <span class="text-danger">*</span>
        @endif
    </div>

    <div class="col-span-2 px-5 py-4">
        {{ $field->formItem() }}

        @if ($errors->has($field->getModelAttribute($resource)))
            <div class="invalid-feedback">{!! $errors->first($field->getModelAttribute($resource)) !!}</div>
        @endif
    </div>
</div>
