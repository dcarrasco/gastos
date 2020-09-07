<div class="row border-bottom py-4">
    <div class="col-3 mt-2 pl-5 {{ $errors->has($field->getModelAttribute($resource)) ? 'text-danger' : 'text-muted' }}">
        <h6 class="font-weight-bold">
            {{ $field->getName() }}

            @if ($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </h6>
    </div>

    <div class="col-6">
        {{ $field->formItem() }}

        @if ($errors->has($field->getModelAttribute($resource)))
            <div class="invalid-feedback">{!! $errors->first($field->getModelAttribute($resource)) !!}</div>
        @endif
    </div>
</div>
