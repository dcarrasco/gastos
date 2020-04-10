@extends('layouts.app_layout')

@section('modulo')

<div class="row">

    <div class="col-md-4 offset-md-4 card">
        <div class="card-body">
            <div class="control-group col-md-10 offset-md-1 col-xs-12 text-center">
                <h2>{{ trans('login.form_change_password') }}</h2>
            </div>

            <div class="control-group col-md-10 offset-md-1 col-xs-12">
                <hr>
            </div>

            <div class="col-md-12">
                <x-alert :errors=$errors />
            </div>

            <div class="col-md-12">
                {{ $msg_alerta }}
            </div>

            <form method="POST" id="frm_login" class="form-horizontal">
                @csrf
                <input type="hidden" name="username" value="{{ request('username') }}">

                @if ($userHasPassword)
                    <div class="control-group col-md-10 offset-md-1 {{ $errors->has('clave_anterior') ? 'has-error' : '' }}">
                        <label class="control-label" for="clave_anterior">{{ trans('login.input_old_password') }}</label>
                        <input type="password" name="clave_anterior" class="form-control input-lg" maxlength="45" autocomplete="off">
                    </div>
                @endif

                <div class="control-group col-md-10 offset-md-1 {{ $errors->has('nueva_clave') ? 'has-error' : '' }}">
                    <label class="control-label" for="nueva_clave">{{ trans('login.input_new1_password') }}</label>
                    <input type="password" name="nueva_clave" class="form-control input-lg" maxlength="45" autocomplete="off">
                    <p class="help-block">
                        <em><small>M&iacute;nimo 8 caracteres. Debe incluir may&uacute;sculas, min&uacute;sculas y n&uacute;meros.</small></em>
                    </p>
                </div>

                <div class="control-group col-md-10 offset-md-1 {{ $errors->has('nueva_clave_confirmation') ? 'has-error' : '' }}">
                    <label class="control-label" for="nueva_clave_confirmation">{{ trans('login.input_new2_password') }}</label>
                    <input type="password" name="nueva_clave_confirmation" class="form-control input-lg" maxlength="45" autocomplete="off">
                </div>

                <div class="control-group col-md-10 offset-md-1">
                    <hr/>
                </div>

                <div class="control-group col-md-10 offset-md-1">
                    <div class="controls">
                        <button type="submit" name="btn_submit" class="col-md-12 btn btn-primary input-lg">
                            <span class="fa fa-lock"></span> {{ trans('login.button_change_password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
