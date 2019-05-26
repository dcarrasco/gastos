<div class="row bg-white border-bottom py-4 rounded-top-lg">
    <div class="col-3 mt-2 pl-5 {{ $errors->has($field->getFieldName($resource)) ? 'text-danger' : 'text-muted' }}">
        <h6 class="font-weight-bold">
            {{ $field->getName() }}
            @if ($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </h6>
    </div>
    <div class="col-6">
        {!! $field->getForm(request(), $resource, [
            'class' => 'form-control' . ($errors->has($field->getFieldName($resource)) ? ' is-invalid' : '')
        ]) !!}

        @if ($errors->has($field->getFieldName($resource)))
            <div class="invalid-feedback">{!! $errors->first($field->getFieldName($resource)) !!}</div>
        @endif
    </div>
</div>
