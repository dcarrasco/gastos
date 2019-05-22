<tr>
<td>
<div class="row">
    <div class="col-md-3 mt-md-2 pl-md-5 font-weight-bold {{ $errors->has($field->getFieldName($resource)) ? 'text-danger' : 'text-muted' }}">
        {{ $field->getName() }}
        @if ($field->isRequired())
            <span class="text-danger">*</span>
        @endif
    </div>
    <div class="col-md-7">
        {!! $field->getForm(request(), $resource, [
            'class' => 'form-control' . ($errors->has($field->getFieldName($resource)) ? ' is-invalid' : '')
        ]) !!}

        @if ($errors->has($field->getFieldName($resource)))
            <div class="invalid-feedback">{!! $errors->first($field->getFieldName($resource)) !!}</div>
        @endif
    </div>
</div>
</td>
</tr>
