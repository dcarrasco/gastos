@extends('layouts.app_layout')

@section('modulo')

<!-- ------------------------- CARDS ------------------------- -->
<x-orm.cards-container :cards="$cards" />

<!-- ------------------------- LABEL ------------------------- -->
<div class="row">
    <div class="col-12">
        <h4>{{ $resource->getLabelPlural() }}</h4>
    </div>
</div>

<!-- ------------------------- SEARCH & NEW ------------------------- -->
{{ Form::open(['class'=>'form-search', 'method'=>'get']) }}
<div class="row pt-2 mb-3 hidden-print">
    <div class="col-4">
        <div id="{{ $resource->urlSearchKey() }}_group" class="input-group input-group-sm bg-white border rounded">
            <svg class="m-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"/></svg>
            {{ Form::text($resource->urlSearchKey(), Request::input($resource->urlSearchKey()), ['class' => 'form-control border-0', 'id' => $resource->urlSearchKey(), 'maxlength' => '30', 'placeholder' => trans('orm.filter')]) }}
        </div>
    </div>

    <div class="col-8 text-right">
        <a href="{{ route($routeName.'.create', [$resource->getName()]) }}" class="btn btn-primary text-right px-3 font-weight-bold text-shadow" id="btn_mostrar_agregar" role="button">
            {{ trans('orm.button_new') }} {{ $resource->getLabel() }}
        </a>
    </div>
</div>
{{ Form::close() }}

<!-- ------------------------- LIST DATA ------------------------- -->
<div class="container shadow-sm rounded-lg border">
    <x-orm.list.filters :resource="$resource" />

    @if ($resource->resourceList()->count() == 0)
        <div class="row">
            <div class="col-12 px-0">
                <div class="card py-5 border-0 rounded-lg">
                    <h1 class="display-1 text-center text-muted">
                        <span class="fa fa-table"></span>
                    </h1>
                    <div class="text-center text-muted">
                        <h5> {{ trans('orm.no_records_found') }} </h5>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 px-0">
                <x-orm.list.table :resource=$resource />
            </div>
        </div>

        <x-paginator.links :resource="$resource" />

    @endif
</div> <!-- container -->

<x-orm.list.modal-delete :resource="$resource" />

<script type="text/javascript">
$(document).ready(function() {
    $('a#delete-href').click(function(e) {
        e.preventDefault();
        $('#formDelete').attr('action', $(this).data('url-form'));
        $('#delete-message').html($(this).data('message'));
    });

    if ($('#{{ $resource->urlSearchKey() }}').val() != '') {
        $('#{{ $resource->urlSearchKey() }}').addClass('search-found');
        $('#{{ $resource->urlSearchKey() }}_group').addClass('search-found');
        $('#{{ $resource->urlSearchKey() }}_group').removeClass('bg-white');
    }
});
</script>

@endsection
