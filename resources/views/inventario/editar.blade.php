@extends('common.app_layout')

@section('modulo')
<div class="row">
	<div class="col-md-8 col-md-offset-2 well">

		{{ Form::open(['class' => 'form-horizontal', 'role' => 'form']) }}
		<fieldset>

		<legend>{{ trans('inventario.form_new') }}</legend>

        @include('orm.validation_errors')

        {{ Form::hidden('hoja', $hoja) }}

        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'ubicacion'])

        <div class="form-group {{ $errors->has('catalogo') ? 'has-error' : '' }}">
            <label class="control-label col-sm-4">
                {{ trans('inventario.form_new_material') }}
                @if( $detalleInventario->isFieldMandatory('catalogo'))
                    <span class="text-danger">*</span>
                @endif
            </label>

            <div class="col-sm-3">
                <div class="input-group">
                    {{ Form::text('agr_filtrar', old('agr_filtrar'), ['class' => 'form-control', 'id' => 'agr_filtrar', 'placeholder' => trans('inventario.form_new_material_placeholder')]) }}
                    <span class="input-group-btn">
                        <div class="btn btn-default">
                            <span class="fa fa-search"></span>
                        </div>
                    </span>
                </div>
            </div>

            <div class="col-md-5">
                {{ Form::select('catalogo', $catalogos, '', ['class' => 'form-control', 'id' => 'agr_material']) }}
            </div>
        </div>

        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'lote'])
        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'um'])
        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'centro'])
        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'almacen'])
        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'stock_fisico'])
        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'hu'])
        @include('orm.form_item', ['modelObject' => $detalleInventario, 'field' => 'observacion'])

		<div class="form-group">
			<label class="control-label col-sm-4">
			</label>
			<div class="col-sm-8">

                <div class="pull-right">
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">
                        <span class="fa fa-check"></span>
                        @if ($detalleInventario->id)
                            {{ trans('inventario.form_new_button_edit') }}
                        @else
                            {{ trans('inventario.form_new_button_add') }}
                        @endif
                    </button>
                    <a href="{{ route('inventario.showHoja', ['hoja' => $hoja]) }}" class="btn btn-default">
                        <span class="fa fa-ban"></span>
                        {{ trans('inventario.form_new_button_cancel') }}
                    </a>
                </div>
              {{ Form::close() }}

                @if ($detalleInventario->id)
                <div class="pull-left">
                    {!! Form::open() !!}
                    {!! method_field('DELETE')!!}
                    <button type="submit" name="accion" value="borrar" class="btn btn-danger">
                        <span class="fa fa-trash-o"></span>
                        {{ trans('inventario.form_new_button_delete') }}
                    </button>
    		      {{ Form::close() }}
                </div>
                @endif
            </div>
        </div>

        </fieldset>
	</div> <!-- fin content-module-main-agregar -->
</div>
<script type="text/javascript" src="{{ asset('js/view_inventario.js') }}"></script>
@endsection
