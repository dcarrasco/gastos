@extends('common.app_layout')

@section('modulo')
<div class="row">
	<div class="col-md-10 col-md-offset-1 well">
		{{ Form::open(['class'=>'form-horizontal']) }}
		{{ Form::hidden('formulario','imprime') }}
		<fieldset>

			<legend>{{ trans('inventario.print_label_legend') }}</legend>

			@include('orm.validation_errors')

			<div class="form-group">
				<label class="control-label col-sm-4">{{ trans('inventario.print_label_inventario') }}</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $inventario->id }} - {{ $inventario }}</p>
				</div>
			</div>

            <div class="form-group {{ $errors->has('pag_desde') ? 'has-error' : '' }}">
                <label class="control-label col-sm-4">{{ trans('inventario.print_label_page_from') }}</label>
                <div class="col-sm-8">
                    {{ Form::text('pag_desde', old('pag_desde', 1), ['class'=>'form-control', 'maxlength'=>5]) }}
                </div>
            </div>

			<div class="form-group {{ $errors->has('pag_hasta') ? 'has-error' : '' }}">
				<label class="control-label col-sm-4">{{ trans('inventario.print_label_page_to') }}</label>
				<div class="col-sm-8">
					{{ Form::text('pag_hasta', old('pag_hasta', $maxHoja), ['class'=>'form-control', 'maxlength'=>5]) }}
				</div>
			</div>

			<div class="form-group {{ $errors->has('oculta_stock_sap') ? 'has-error' : '' }}">
				<label class="control-label col-sm-4">{{ trans('inventario.print_label_options') }}</label>
				<div class="col-sm-8">
					<label class="checkbox-inline">
						{{ Form::checkbox('oculta_stock_sap', 'oculta_stock_sap', old('oculta_stock_sap')) }}
						{{ trans('inventario.print_check_hide_columns') }}
					</label>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4">
				</label>
				<div class="col-sm-8">
					<button name="submit" type="submit" class="btn btn-primary pull-right" id="btn_imprimir">
						<span class="fa fa-print"></span>
						{{ trans('inventario.print_button_print') }}
					</button>
				</div>
			</div>

		</fieldset>
		{{ Form::close() }}
	</div>
</div>
@endsection