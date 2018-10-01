<tr>
<td>
<div class="row">
    <div class="col-md-3 pl-md-5 text-muted">
        {{ $field->getName() }}
    </div>
    <div class="col-md-9">
        {!! $field->getValue(request(), $resource->model()) !!}
    </div>
</div>
</td>
</tr>
