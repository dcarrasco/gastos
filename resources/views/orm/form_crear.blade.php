@extends('common.app_layout')

@section('modulo')

<div class="container">

    <div class="row pt-4">
        <div class="col-12">
            <h4>
                {{ trans('orm.title_add') }}
                {{ $resource->getLabel() }}
            </h4>
        </div>
    </div>

    <div class="container mt-4 border rounded-lg bg-white shadow-sm">
        {{ Form::open(['url' => route($routeName.'.store', [$resource->getName()]), 'id' => 'frm_editar']) }}

        @each('orm.item_form', $fields, 'field')

        <div class="row">
            <div class="col-12 bg-light rounded-bottom-lg py-4 text-right text-shadow">
                <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold" id="button_continue">
                    {{ trans('orm.button_create_continue') }}
                </button>

                <button type="submit" class="btn btn-primary btn-sm btn-sm px-3 font-weight-bold text-shadow">
                    {{ trans('orm.button_create') }} {{ $resource->getLabel() }}
                </button>
           </div>
       </div>

        {!! Form::hidden('redirect_to', 'next') !!}
        {!! Form::close()!!}
    </div>

</div> <!-- CONTAINER -->

<script>
    $("#button_continue").click(function(event) {
        event.preventDefault;
        $("#frm_editar input[name='redirect_to']").val('same');
        $("#frm_editar").submit();
    })
</script>

@endsection
