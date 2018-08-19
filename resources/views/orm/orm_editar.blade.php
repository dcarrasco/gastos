@extends('common.app_layout')

@section('modulo')
<div class="row">
<div class="col-md-10 offset-md-1 card bg-light my-md-4">
{!! Form::open(['url' => $formURL, 'id' => 'frm_editar', ' role' => 'form']) !!}

    <div class="card-header row">
        <legend>
            {!! $accionForm !!}
            {!! $modelObject->modelLabel !!}
        </legend>
    </div>

    <div class="card-body row">
        @if ($createOrEdit === 'edit')
        	{!! method_field('PUT')!!}
        @endif

		<fieldset class="offset-md-1 col-md-10">
            @include('orm.validation_errors')
	       	@foreach($modelObject->getModelFields() as $field => $fieldData)
                @include('orm.form_item')
			@endforeach
        </fieldset>
   </div>

    <div class="card-footer row">
		<label class="col-form-label col-md-4"></label>

		<div class="col-md-8">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<span class="fa fa-check"></span> {{ $accionForm }}
				</button>

				<a href="{{ route($routeName.'.index', [$modelName]) }}" class="btn btn-outline-secondary" role="button">
					<span class="fa fa-ban"></span> {{ trans('orm.button_cancel') }}
				</a>
			</div>
{!! Form::close()!!}

	        @if ($createOrEdit === 'edit')
			<div class="pull-left">
				{!! Form::open(['url' => route($routeName.'.destroy', [$modelName, $modelID])]) !!}
				{!! method_field('DELETE')!!}
				<button type="submit" class="btn btn-danger" name="borrar" value="borrar" onclick="return confirm('{{ trans('orm.js_delete_confirm', ['model' => $modelObject->modelLabel, 'item' => (string) $modelObject]) }}');">
					<span class="fa fa-trash-o"></span>
					{{ trans('orm.button_delete') }}
				</button>
				{!! Form::close() !!}
			</div>
			@endif

		</div>
	</div>
</div> <!-- DIV   class="card" -->
</div> <!-- DIV   class="row" -->
@endsection
