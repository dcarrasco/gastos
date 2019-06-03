@extends('common.app_layout')

@section('modulo')
<div class="container">

    <div class="row pt-4">
        <div class="col-12">
            <h4>
            {{ trans('orm.title_edit') }}
            {{ $resource->getLabel() }}
            </h4>
        </div>
    </div>

    <div class="container mt-4 border rounded-lg bg-white shadow-sm">
        {{ Form::open([
            'url' => route($routeName.'.update', [$resource->getName(), $modelId]),
            'id' => 'frm_editar',
        ]) }}
        {{ method_field('PUT') }}

        @foreach($resource->detailFields(request()) as $field)
            @include('orm.item_form')
        @endforeach

        <div class="row">
            <div class="col-12 bg-light rounded-bottom-lg py-4 text-right">
                <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold text-shadow" id="button_continue">
                    {{ trans('orm.button_update_continue') }}
                </button>

                <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold text-shadow">
                    {{ trans('orm.button_update') }} {{ $resource->getLabel() }}
                </button>
            </div>
        </div>

        {!! Form::hidden('redirect_to', 'next') !!}
        {!! Form::close()!!}
    </div>
</div>

<script>
    $("#button_continue").click(function(event) {
        event.preventDefault;
        $("#frm_editar input[name='redirect_to']").val('same');
        $("#frm_editar").submit();
    })
</script>

@endsection
