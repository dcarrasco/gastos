@extends('common.app_layout')

@section('modulo')

<div class="row">

	<div class="col-md-6 col-md-offset-3 col-xs-12 well">
		<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
			<h2>{{ trans('login.form_title') }}</h2>
		</div>

		<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
			<hr>
		</div>

		<div class="col-md-12">
	        @include('orm.validation_errors')
		</div>

		{{ Form::open(['id' => 'frm_login', 'class' => 'form-horizontal']) }}

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12 {{ $errors->has('usr') ? 'has-error' : '' }}">
				<label class="control-label" for="usr">
					{{ trans('login.input_user') }}
				</label>
				<div class="controls">
					{{ Form::text('usr', old('usr'), ['maxlength' => '45', 'class' => 'form-control', 'tabindex' => '1', 'autofocus' => 'autofocus']) }}
				</div>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12 {{ $errors->has('password') ? 'has-error' : '' }}">
				<label class="control-label" for="pwd">
					{{ trans('login.input_password') }}
				</label>
				<div class="controls">
					{{ Form::password('password', ['maxlength' => '45', 'size' => '40', 'tabindex' => '2', 'class' => 'form-control', 'autocomplete' => 'off']) }}
				</div>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
				<div class="pull-right">
					<a href="#" id="lnk_cambio_password")>{{ trans('login.link_change_password') }}</a>
				</div>
			</div>

			@if (false) // $usar_captcha
				<div class="control-group col-md-8 col-md-offset-2 col-xs-12 <?= form_has_error_class('catpcha'); ?>">
					<label class="control-label" for="pwd">{{ trans('login.input_captcha') }}</label>
					<div class="controls">
						<?= form_input('captcha', '','maxlength="15" tabindex="3" class="form-control"'); ?>
					</div>
					<div class="controls">
						{captcha_img}
					</div>
				</div>
			@endif

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
				<div class="checkbox">
					<label>
						{{ Form::checkbox('remember', 'remember', old('remember')) }}
						{{ trans('login.check_remember_me') }}
					</label>
				</div>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
				<hr>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
				<button type="submit" name="btn_submit" class="btn btn-success pull-right col-md-6">
					{{ trans('login.button_login') }} &nbsp; <span class="fa fa-sign-in"></span>
				</button>
			</div>
			<a class="btn btn-link" href="{{ url('/password/reset') }}">
				Forgot Your Password?
			</a>
		{{ Form::close() }}
	</div>

</div>

{{ Form::open(['url' => 'login/cambio_password', 'id' => 'frm_cambio_password']) }}
{{ Form::hidden('usr','') }}
{{ Form::close() }}

<script type="text/javascript">
	$(document).ready(function () {

		$('#lnk_cambio_password').click(function(e) {
			e.preventDefault();
			$('#frm_cambio_password input[name="usr"]').val($('#frm_login input[name="usr"]').val());
			$('#frm_cambio_password').submit();
		});

	});
</script>
@endsection