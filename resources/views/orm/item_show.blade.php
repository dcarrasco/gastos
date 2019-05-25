<div class="col-md-12 bg-white">
    <div class="row py-md-4 border-bottom">
        <div class="col-md-3 pl-md-5 text-muted">
            {{ $field->getName() }}
        </div>
        <div class="col-md-9">
            {!! $field->getValue($resource->model()) !!}
        </div>
    </div>
</div>
