<div class="form-group row {{ $errors->has($field->getField()) ? 'is-invalid' : '' }}">
    <label for="id_nombre" class="col-form-label col-md-3">
        {!! $field->getName() !!}
        @if ($field->isRequired())
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="col-md-9">
        {!! $field->getForm($modelObject, ['class' => 'form-control']) !!}
        <small class="form-text text-muted">
           <em>{!! $field->getHelpText() !!}</em>
        </small>
    </div>
</div>
