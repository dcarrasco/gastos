@extends('common.app_layout')

@section('modulo')

<div class="row">

    <div class="col-md-4 offset-md-4 card">
        <div class="card-body">

        <div class="control-group col-md-10 offset-md-1 col-xs-12">
            <h2 class="text-center">{{ trans('login.form_title') }}</h2>
        </div>

        <div class="control-group col-md-10 offset-md-1 col-xs-12">
            <hr>
        </div>

        <div class="col-md-12">
            @include('common.alert')
        </div>

        {{ Form::open(['id' => 'form_login']) }}

            <div class="form-group col-md-10 offset-md-1 col-xs-12 {{ $errors->has('username') ? 'has-error' : '' }}">
                <label for="username">
                    {{ trans('login.input_user') }}
                </label>
                {{ Form::text('username', old('username'), ['maxlength' => '45', 'class' => 'form-control form-control-lg', 'tabindex' => '1', 'autofocus' => 'autofocus']) }}
            </div>

            <div class="form-group col-md-10 offset-md-1 col-xs-12 {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="pwd">
                    {{ trans('login.input_password') }}
                </label>
                {{ Form::password('password', ['maxlength' => '45', 'size' => '40', 'tabindex' => '2', 'class' => 'form-control form-control-lg', 'autocomplete' => 'off']) }}
            </div>

            <div class="control-group col-md-10 offset-md-1 col-xs-12">
                <div class="pull-right">
                    <a href="#" id="link_cambia_password">{{ trans('login.link_change_password') }}</a>
                </div>
            </div>

            @if (false) // $usar_captcha
                <div class="control-group col-md-10 offset-md-1 col-xs-12 <?= form_has_error_class('catpcha'); ?>">
                    <label class="control-label" for="pwd">{{ trans('login.input_captcha') }}</label>
                    <div class="controls">
                        <?= form_input('captcha', '','maxlength="15" tabindex="3" class="form-control input-lg"'); ?>
                    </div>
                    <div class="controls">
                        {captcha_img}
                    </div>
                </div>
            @endif

            <div class="custom-control custom-checkbox col-md-10 offset-md-2">
                {{ Form::checkbox('remember', 'remember', old('remember'), ['class' => 'custom-control-input', 'id' => 'remember-id']) }}
                <label class="custom-control-label" for="remember-id">
                    {{ trans('login.check_remember_me') }}
                </label>
            </div>

            <div class="control-group col-md-10 offset-md-1 col-xs-12">
                <hr>
            </div>

            <div class="control-group col-md-10 offset-md-1 col-xs-12">
                <button type="submit" name="btn_submit" class="btn btn-success btn-lg col-md-12">
                    {{ trans('login.button_login') }} &nbsp; <span class="fa fa-sign-in"></span>
                </button>
            </div>
        {{ Form::close() }}
        </div>
    </div>

    {{ Form::open(['method' => 'get', 'id' => 'form_cambia_password', 'route' => 'acl.cambiaPassword']) }}
    {{ Form::hidden('username') }}
    {{ Form::close() }}
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#link_cambia_password').click(function(e) {
                e.preventDefault();
                if ($('#form_login input[name="username"]').val() !== '') {
                    $('#form_cambia_password input[name="username"]').val($('#form_login input[name="username"]').val());
                    $('#form_cambia_password').submit();
                } else {
                    alert('Debe ingresa un nombre de usuario para cambiarle la clave');
                }
            });
        });
    </script>
</div>

@endsection
