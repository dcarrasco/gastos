<div class="form-group {{ $errors->has($field) ? 'has-error' : '' }}">
    <label for="id_nombre" class="control-label col-sm-4">
        {{ $modelObject->getFieldLabel($field) }}
        @if ($modelObject->isFieldMandatory($field))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="col-sm-8">
        {!! $modelObject->getFieldForm($field, ['class' => 'form-control']) !!}
            <span class="help-block">
           <em><small>
                {{ $modelObject->getFieldHelp($field) }}
           </small></em>
        </span>
    </div>
</div>
