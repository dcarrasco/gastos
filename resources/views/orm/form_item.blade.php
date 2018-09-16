<div class="form-group row">
    <label for="id_nombre" class="col-form-label col-md-3">
        {!! $field->getName() !!}
        @if ($field->isRequired())
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="col-md-9">
        {!! $field->getForm($modelObject, [
            'class' => 'form-control' . ($errors->has($field->getField()) ? ' is-invalid' : '')
        ]) !!}

        @if ($errors->has($field->getField()))
            <div class="invalid-feedback">{!! $errors->first($field->getField()) !!}</div>
        @endif
    </div>
</div>
