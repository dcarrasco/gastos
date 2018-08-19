<div class="form-group row {{ $errors->has($field) ? 'has-error' : '' }}">
    <label for="id_nombre" class="col-form-label col-md-3 offset-md-1">
        {{ $modelObject->getFieldLabel($field) }}
        @if ($modelObject->isFieldMandatory($field))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="col-md-7">
        {!! $modelObject->getFieldForm($field, ['class' => 'form-control']) !!}
        <small class="form-text text-muted">
           <em>{{ $modelObject->getFieldHelp($field) }}</em>
        </small>
    </div>
</div>
