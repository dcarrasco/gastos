<div class="form-group row {{ $errors->has($field) ? 'is-invalid' : '' }}">
    <label for="id_nombre" class="col-form-label col-md-3">
        {!! $modelObject->getFieldLabel($field) !!}
        @if ($modelObject->isFieldMandatory($field))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="col-md-9">
        {!! $modelObject->getFieldForm($field, ['class' => 'form-control']) !!}
        <small class="form-text text-muted">
           <em>{!! $modelObject->getFieldHelp($field) !!}</em>
        </small>
    </div>
</div>
