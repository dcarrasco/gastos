@extends('layouts.app_layout')

@section('modulo')
<div class="row">
    <div class="col-12">
        <h4>
            {{ trans('orm.title_edit') }}
            {{ $resource->getLabel() }}
        </h4>
    </div>
</div>

<div class="container mt-2 border rounded-lg bg-white shadow-sm">
    {{ Form::open([
        'url' => route("$routeName.update", [$resource->getName(), $modelId]),
        'id' => 'frm_editar',
    ]) }}
    {{ method_field('PUT') }}

    <!-- -----------------------------  FIELDS  ---------------------------- -->
    @foreach($resource->getFields() as $field)
        <x-orm.item-form :field=$field />
    @endforeach

    <!-- -----------------------------  BOTONES  --------------------------- -->
    <div class="row">
        <div class="col-12 bg-light rounded-bottom-lg py-4 text-right">
            <button type="submit" class="btn btn-primary px-3 mx-2 font-weight-bold text-shadow" id="button_continue">
                {{ trans('orm.button_update_continue') }}
            </button>

            <button type="submit" class="btn btn-primary px-3 mx-2 font-weight-bold text-shadow">
                {{ trans('orm.button_update') }} {{ $resource->getLabel() }}
            </button>
        </div>
    </div>

    {{ Form::hidden('redirect_to', 'next') }}
    {{ Form::close() }}
</div>

<script>
    $("#button_continue").click(function(event) {
        event.preventDefault;
        $("#frm_editar input[name='redirect_to']").val('same');
        $("#frm_editar").submit();
    })
</script>
@endsection
