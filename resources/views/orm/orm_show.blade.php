@extends('common.app_layout')

@section('modulo')
<div class="row mt-md-4 mx-md-5">

    <div class="col-md-10 my-md-2">
        <h4>
            {{ trans('orm.title_show') }}
            {!! $resource->getLabel() !!}
        </h4>
    </div>

    <div class="col-md-2 my-md-2 text-right">
        <a href="" class="btn btn-light border py-md-1" data-toggle="modal" data-target="#modalBorrar">
            <svg style="fill: #888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
        </a>
        <a href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}" class="btn btn-primary border py-md-1">
            <svg style="fill: #EEE" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path class="heroicon-ui" d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/></svg>
        </a>
    </div>

    <div class="col-md-12 bg-white rounded">
        @foreach($resource->detailFields(request()) as $field)
            @include('orm.item_show')
        @endforeach
    </div>

</div>

@include('orm.orm_modal_delete')

@endsection
