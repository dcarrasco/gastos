@extends('common.app_layout')

@section('modulo')
{!! Form::open(['class'=>'form-search col-md-12', 'method'=>'get']) !!}
<div class="row hidden-print orm-list-header">
    <div class="col-md-3">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <button type="submit" id="btn_filtro" class="btn btn-outline-secondary">
                    <span class="fa fa-search"></span>
                </button>
            </div>
            {!! Form::text('filtro', Request::input('filtro'), ['class' => 'form-control', 'id' => 'filtro', 'maxlength' => '30', 'placeholder' => trans('orm.filter')]); !!}
        </div>
    </div>

    <div class="col-md-9 text-right">
        <a href="{{ route($routeName.'.create', [$modelName]) }}" class="btn btn-primary btn-sm text-right" id="btn_mostrar_agregar" role="button">
            <span class="fa fa-plus-circle"></span>
            {{ trans('orm.button_new') }} {{ $modelObject->getLabel() }}
        </a>
    </div>
</div>
{!! Form::close() !!}

<div>
    @if ($modelCollection->count() == 0)
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
        @foreach ($modelCollection as $modelElem)
            @if ($loop->first)
                <thead class="thead-light">
                    <tr>
                        @foreach($modelElem->indexFields() as $field)
                        <th class="text-uppercase">
                            <small><strong>{!! $field->getName() !!}</strong></small>
                            {!! $field->getSortingIcon() !!}
                        </th>
                        @endforeach
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
            @endif

            <tr>
                @foreach($modelElem->indexFields() as $field)
                    <td>{!! $field->getFormattedValue($modelElem->{$field->getField()}) !!}</td>
                @endforeach
                <td class="text-center">
                    <a href="{{ route($routeName.'.edit', [$modelName, $modelElem->getKey()]) }}" class="text-muted">
                        {{ trans('orm.link_edit') }}
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

<script type="text/javascript">
$(document).ready(function() {
    if ($('#filtro').val() != '')
    {
        $('#filtro').addClass('search_found');
        $('#btn_filtro').removeClass('btn-default');
        $('#btn_filtro').addClass('btn-primary');
    }
});
</script>
@endsection
