<div class="accordion hidden-print">
    {{ Form::open(['id'=>'frm_param']) }}
    <div class="panel panel-default">

        <div class="panel-heading">
            <div class="row">
                <div class="col-md-8">
                    <a href="#form_param" class="accordion-toggle" data-toggle="collapse">
                        {{ trans('toa.consumo_parametros') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="panel-body collapse in" id="form_param">
            <div class="accordion-inner">

                @include('orm.validation_errors')

                <div class="row">
                    <div class="col-md-3 form_group {{ $errors->has('empresa') ? 'has-error' : '' }}">
                        <label class="control-label">{{ trans('toa.controles_tecnicos_empresas') }}</label>
                        {{ Form::select('empresa', $empresas, request()->input('empresa'), ['class'=>'form-control']) }}
                    </div>

                    <div class="col-md-2 form_group {{ $errors->has('mes') ? 'has-error' : '' }}">
                        <label class="control-label">{{ trans('toa.controles_tecnicos_meses') }}</label>
                        <div class="input-group date" data-provide="datepicker" data-date-min-view-mode="1" data-date-language="es" data-date-autoclose="true" data-date-format="yyyymm">
                            {{ Form::text('mes', request()->input('mes'), ['class'=>'form-control', 'placeholder'=>'Seleccione mes...']) }}
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>

                    <div class="col-md-3 form_group {{ $errors->has('filtro_trx') ? 'has-error' : '' }}">
                        <label class="control-label">{{ trans('toa.controles_tecnicos_filtro_trx') }}</label>
                        {{ Form::select('filtro_trx', $transacciones, request()->input('filtro_trx'), ['class'=>'form-control']) }}
                    </div>

                    <div class="col-md-2 form_group {{ $errors->has('dato') ? 'has-error' : '' }}">
                        <label class="control-label">{{ trans('toa.controles_tecnicos_dato_desplegar') }}</label>
                        {{ Form::select('dato', $unidadesConsumo, request()->input('dato'), ['class'=>'form-control']) }}
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="pull-right btn btn-primary">
                            <span class="fa fa-search"></span>
                            {{ trans('toa.consumo_btn_reporte') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
