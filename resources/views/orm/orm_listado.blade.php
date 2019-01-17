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
                    <span class="fa fa-search"></span>
                </button>
            </div>
            {!! Form::text('filtro', Request::input('filtro'), ['class' => 'form-control', 'id' => 'filtro', 'maxlength' => '30', 'placeholder' => trans('orm.filter')]); !!}
        </div>
    </div>

    <div class="col-md-9 text-right">
        <a href="{{ route($routeName.'.create', [$resource->getName()]) }}" class="btn btn-primary btn-sm text-right" id="btn_mostrar_agregar" role="button">
            <span class="fa fa-plus-circle"></span>
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
                    <a class="btn py-md-0 px-md-1 text-muted" href="{{ route($routeName.'.show', [$resource->getName(), $model->getKey()]) }}"><span class="fa fa-eye"></span></a>
                    <a class="btn py-md-0 px-md-1 text-muted" href="{{ route($routeName.'.edit', [$resource->getName(), $model->getKey()]) }}"><span class="fa fa-edit"></span></a>
                    <a class="btn py-md-0 px-md-1 text-muted" data-toggle="modal" data-target="#modalBorrar" data-url-form="{!! route($routeName.'.destroy', [$resource->getName(), $model->getKey()]) !!}"" id="delete-href"><span class="fa fa-trash"></span></a>
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
