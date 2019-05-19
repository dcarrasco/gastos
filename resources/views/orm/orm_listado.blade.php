@extends('common.app_layout')

@section('modulo')

@include('orm.cardsContainer')

<div class="row col-md-12 pt-md-2">
    <h4>{{ $resource->getLabelPlural() }}</h4>
</div>

{!! Form::open(['class'=>'form-search', 'method'=>'get']) !!}
<div class="row hidden-print py-md-2">
    <div class="col-md-3">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <button type="submit" id="btn_filtro" class="btn btn-light border">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"/></svg>
                </button>
            </div>
            {!! Form::text('filtro', Request::input('filtro'), ['class' => 'form-control', 'id' => 'filtro', 'maxlength' => '30', 'placeholder' => trans('orm.filter')]); !!}
        </div>
    </div>

    <div class="col-md-9 text-right">
        <a href="{{ route($routeName.'.create', [$resource->getName()]) }}" class="btn btn-primary btn-sm text-right px-md-3 font-weight-bold" id="btn_mostrar_agregar" role="button">
            {{ trans('orm.button_new') }} {{ $resource->getLabel() }}
        </a>
    </div>
</div>
{!! Form::close() !!}

@include('orm.filters')

<div>
    @if ($modelList->count() == 0)
        <div class="card py-md-5">
            <h1 class="display-1 text-center">
                <span class="fa fa-table"></span>
            </h1>
            <div class="text-center">
                {!! trans('orm.no_records_found') !!}
            </div>
        </div>
    @else
    <table class="table table-hover">
        @foreach ($modelList as $model)
            @if ($loop->first)
                <thead class="thead-light">
                    <tr>
                        @foreach($resource->indexFields(request()) as $field)
                        <th class="text-uppercase">
                            <small><strong>{!! $field->getName() !!}</strong></small>
                            {!! $field->getSortingIcon(request(), $resource) !!}
                        </th>
                        @endforeach
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
            @endif

            <tr>
                @foreach($resource->indexFields(request()) as $field)
                    <td>{!! $field->getValue(request(), $model) !!}</td>
                @endforeach
                <td class="text-right">
                    <a class="btn py-md-0 px-md-1 text-muted" href="{{ route($routeName.'.show', [$resource->getName(), $model->getKey()]) }}">
                        <svg style="fill: #888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                    </a>
                    <a class="btn py-md-0 px-md-1 text-muted" href="{{ route($routeName.'.edit', [$resource->getName(), $model->getKey()]) }}">
                        <svg style="fill: #888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/></svg>
                    </a>
                    <a class="btn py-md-0 px-md-1 text-muted" data-toggle="modal" data-target="#modalBorrar" data-url-form="{!! route($routeName.'.destroy', [$resource->getName(), $model->getKey()]) !!}"" id="delete-href">
                        <svg style="fill: #888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row justify-content-md-center">
        {{ $paginationLinks }}
    </div>

    @endif
</div>

@include('orm.orm_modal_delete')

<script type="text/javascript">
$(document).ready(function() {

    $('a#delete-href').click(function(e) {
        e.preventDefault();
        $('#formDelete').attr('action', $(this).data('url-form'));
    });


    if ($('#filtro').val() != '')
    {
        $('#filtro').addClass('search_found');
        $('#btn_filtro').removeClass('btn-default');
        $('#btn_filtro').addClass('btn-primary');
    }
});
</script>
@endsection
