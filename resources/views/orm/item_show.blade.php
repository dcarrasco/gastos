<div class="row py-4 border-bottom">

    <div class="col-3 pl-5 text-muted">
        <h6 class="font-weight-bold">
            {{ $field->getName() }}
        </h6>
    </div>

    <div class="col-9">
        <h6>
            {!! $field->getValue($resource->model()) !!}
        </h6>
    </div>

</div>
