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
            {!! trans('orm.button_new') !!} {!! strtolower($modelObject->modelLabel) !!}
        </a>
    </div>
</div>
{!! Form::close() !!}

<div>
    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                @foreach($modelObject->getFieldsList() as $field)
                <th class="text-uppercase">
                    <small><strong>{!! $modelObject->getFieldLabel($field) !!}</strong></small>
                    {!! $modelObject->getFieldSortingIcon($field) !!}
                </th>
                @endforeach
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            @if ($modelCollection->count() == 0):
            <tr>
                <td class="text-muted text-center" colspan=100>
                    <h1 class="display-1">
                        <span class="fa fa-table"></span>
                    </h1>
                    {!! trans('orm.no_records_found') !!}
                </td>
            </tr>
            @endif

            @foreach ($modelCollection as $modelElem)
            <tr>
                @foreach($modelObject->getFieldsList() as $field)
                <td>{!! $modelElem->getFormattedFieldValue($field) !!}</td>
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
