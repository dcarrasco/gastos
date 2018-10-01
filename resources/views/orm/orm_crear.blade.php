@extends('common.app_layout')

@section('modulo')
<div class="row mt-md-2 col-md-12 my-md-2">
    <h4>
        {{ trans('orm.title_add') }}
        {{ $resource->getLabel() }}
    </h4>
</div>

{{ Form::open(['url' => route($routeName.'.store', [$resource->getName()]), 'id' => 'frm_editar']) }}
<div class="row">
    <div class="col-md-12 my-md-2">

        <table class="table">
            <tbody>
                @foreach($resource->detailFields(request()) as $field)
                    @include('orm.item_form')
                @endforeach

                <tr class="bg-light">
                    <td class="text-right">
                        <button type="submit" class="btn btn-primary btn-sm" id="button_continue">
                            {{ trans('orm.button_create_continue') }}
                        </button>

                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ trans('orm.button_create') }} {{ $resource->getLabel() }}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
   </div>
</div> <!-- DIV   class="row" -->
{!! Form::hidden('redirect_to', 'next') !!}
{!! Form::close()!!}

<script>
    $("#button_continue").click(function(event) {
        event.preventDefault;
        $("#frm_editar input[name='redirect_to']").val('same');
        $("#frm_editar").submit();
    })
</script>

@endsection
