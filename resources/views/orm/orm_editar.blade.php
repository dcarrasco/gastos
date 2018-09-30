@extends('common.app_layout')

@section('modulo')
<div class="row mt-md-2 col-md-12 my-md-2">
    <h4>
        {!! $accionForm !!}
        {!! $resource->getLabel() !!}
    </h4>
</div>

{!! Form::open(['url' => $formURL, 'id' => 'frm_editar', ' role' => 'form']) !!}
<div class="row">
    <div class="col-md-12 my-md-2">

        @if ($createOrEdit === 'edit')
        	{!! method_field('PUT')!!}
        @endif

        <table class="table">
            <tbody>
                {{-- @include('orm.validation_errors') --}}
    	       	@foreach($resource->detailFields(request()) as $field)
                    @include('orm.form_item')
    			@endforeach

                <tr class="bg-light">
                    <td class="text-right">
                        <button type="submit" class="btn btn-primary btn-sm" id="button_continue">
                            {{ $buttonActionContinue }}
                        </button>

                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ $buttonAction }}
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
