<div class="row border-bottom py-4">
    <div class="col-3 mt-2 pl-5 {{ $errors->has($field->getAttribute()) ? 'text-danger' : 'text-muted' }}">
        <h6 class="font-weight-bold">
            {{ $field->getName() }}

            @if ($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </h6>
    </div>

    <div class="col-6">
        {{ $field->formItem() }}

        @if ($errors->has($field->getAttribute()))
            <div class="invalid-feedback">{!! $errors->first($field->getAttribute()) !!}</div>
        @endif
    </div>
</div>
