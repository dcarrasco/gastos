<tr><td>
<div class="row">
    <div class="col-md-3 mt-md-2 text-muted">
        {{ $field->getName() }}
        @if ($field->isRequired())
            <span class="text-danger">*</span>
        @endif
    </div>
    <div class="col-md-7">
        {!! $field->getForm(request(), $resource, [
            'class' => 'form-control' . ($errors->has($field->getField($resource)) ? ' is-invalid' : '')
        ]) !!}

        @if ($errors->has($field->getField($resource)))
            <div class="invalid-feedback">{!! $errors->first($field->getField($resource)) !!}</div>
        @endif
    </div>
</div>
</td></tr>
