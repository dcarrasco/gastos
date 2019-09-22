@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show mt-3 shadow-sm" role="alert">
    <button type="button" class="close text-monospace" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    <h6>
        <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
        <strong>ERROR</strong>
    </h6>

    <ul>
        @foreach ($errors->all() as $error)
        <li>{!! $error !!}</li>
        @endforeach
    </ul>
</div>
@endif
