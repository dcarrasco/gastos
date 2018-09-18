@extends('common.app_layout')

@section('modulo')
<div class="row">
<div class="col-md-10 offset-md-1 my-md-2">
    <legend>
        {!! $accionForm !!}
        {!! $resource->getLabel() !!}
    </legend>
</div>
<div class="col-md-10 offset-md-1 card bg-light">
{!! Form::open(['url' => $formURL, 'id' => 'frm_editar', ' role' => 'form']) !!}

    <div class="card-body row">
        @if ($createOrEdit === 'edit')
        	{!! method_field('PUT')!!}
        @endif

		<fieldset class="offset-md-1 col-md-10">
            {{-- @include('orm.validation_errors') --}}
	       	@foreach($resource->detailFields() as $field)
                @include('orm.form_item')
			@endforeach
        </fieldset>
   </div>

    <div class="card-footer row">
        <div class="col-md-10 offset-md-1 row">
            <label class="col-form-label col-md-3"></label>
            <div class="col-md-9">
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary">
                        <span class="fa fa-check"></span> {{ $accionForm }}
                    </button>

                    <a href="{{ route($routeName.'.index', [$resource->getName()]) }}" class="btn btn-outline-secondary" role="button">
                        <span class="fa fa-ban"></span> {{ trans('orm.button_cancel') }}
                    </a>
                </div>

                @if ($createOrEdit === 'edit')
                <div class="pull-left">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalBorrar">
                        <span class="fa fa-trash-o"></span>
                        {{ trans('orm.button_delete') }}
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

{!! Form::close()!!}
</div> <!-- DIV   class="card" -->
</div> <!-- DIV   class="row" -->

<!-- Modal -->
@if ($createOrEdit === 'edit')
<div class="modal fade" id="modalBorrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalCenterTitle">
                Borrar {{ $resource->getLabel() }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            {!! trans('orm.delete_confirm', ['model' => $resource->getLabel(), 'item' => $resource->title()]) !!}
        </div>

        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                {{ trans('orm.button_cancel') }}
            </button>
            {!! Form::open(['url' => route($routeName.'.destroy', [$resource->getName(), $modelId])]) !!}
            {!! method_field('DELETE')!!}
            <button type="submit" class="btn btn-danger" name="borrar" value="borrar">
                <span class="fa fa-trash-o"></span>
                {{ trans('orm.button_delete') }}
            </button>
            {!! Form::close() !!}
        </div>
    </div>
    </div>
</div>
@endif

@endsection
