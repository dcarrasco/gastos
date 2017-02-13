@extends('common.app_layout')

@section('modulo')
<div class="row hidden-print">
    {!! Form::open(['class'=>'form-search', 'method'=>'get']) !!}
    <div class="col-md-3 col-sm-5 col-xs-6">
        <div class="input-group input-group-sm">
            {!! Form::text('filtro', Request::input('filtro'), ['class' => 'form-control', 'id' => 'filtro', 'maxlength' => '30', 'placeholder' => trans('orm.filter')]); !!}
            <span class="input-group-btn">
                <button type="submit" id="btn_filtro" class="btn btn-default">
                    <span class="fa fa-search"></span>
                </button>
            </span>
        </div>
    </div>

    <div class="col-md-9 col-sm-7 col-xs-6 text-right">
        <a href="{{ route($routeName.'.create', [$modelName]) }}" class="btn btn-primary" id="btn_mostrar_agregar" role="button">
            <span class="fa fa-plus-circle"></span>
            {{ trans('orm.button_new') }} {{ strtolower($modelObject->modelLabel) }}
        </a>
    </div>
    {!! Form::close() !!}
</div>

<div>
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                @foreach($modelObject->getFieldsList() as $field)
                <th>{{ $modelObject->getFieldLabel($field) }}</th>
                @endforeach
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modelCollection as $modelElem)
            <tr>
                @foreach($modelObject->getFieldsList() as $field)
                <td>{!! $modelElem->getFormattedFieldValue($field) !!}</td>
                @endforeach
                <td class="text-center">
                    <a href="{{ route($routeName.'.edit', [$modelName, $modelElem->getKey()]) }}" class="">
                        {{ trans('orm.link_edit') }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $modelCollection->links() }}
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
