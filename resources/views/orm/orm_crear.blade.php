@extends('common.app_layout')

@section('modulo')
<div class="row py-md-4 px-md-4">
    <div class="col-md-12 my-md-3">
        <h4>
            {{ trans('orm.title_add') }}
            {{ $resource->getLabel() }}
        </h4>
    </div>

    <div class="col-md-12 px-md-3 rounded-lg bg-white shadow-sm">
        {{ Form::open(['url' => route($routeName.'.store', [$resource->getName()]), 'id' => 'frm_editar']) }}
        @foreach($resource->detailFields(request()) as $field)
            @include('orm.item_form')
        @endforeach

        <div class="row">
        <div class="col-md-12 bg-light rounded-bottom-lg py-md-4 text-right">
            <button type="submit" class="btn btn-primary btn-sm px-md-3 font-weight-bold" id="button_continue">
                {{ trans('orm.button_create_continue') }}
            </button>

            <button type="submit" class="btn btn-primary btn-sm btn-sm px-md-3 font-weight-bold">
                {{ trans('orm.button_create') }} {{ $resource->getLabel() }}
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
