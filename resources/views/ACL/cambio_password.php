<div class="row">

	<div class="col-md-6 col-md-offset-3 well">
		<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
			<h2>{_login_form_change_password_}</h2>
		</div>

		<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
			<hr>
		</div>

		<div class="col-md-12">
			{validation_errors}
		</div>

		<div class="col-md-12">
			{msg_alerta}
		</div>

		<?= form_open('login/cambio_password/' . $usr, 'id="frm_login" class="form-horizontal"'); ?>


			<div class="control-group col-md-8 col-md-offset-2 <?= form_has_error_class('usr'); ?>">
				<label class="control-label" for="usr">{_login_input_user_}</label>
				<div class="controls">
					<?= form_input('usr', set_value('usr', $usr),'maxlength="45" class="form-control"'); ?>
				</div>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 <?= form_has_error_class('pwd_old'); ?>">
				<label class="control-label" for="pwd_old">{_login_input_old_password_}</label>
				<div class="controls">
					<?= form_password('pwd_old', '','maxlength="45" autocomplete="off" class="form-control" ' . $tiene_clave_class); ?>
				</div>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 <?= form_has_error_class('pwd_new1'); ?>">
				<label class="control-label" for="pwd_new1">{_login_input_new1_password_}</label>
				<div class="controls">
					<?= form_password('pwd_new1', '','maxlength="45" class="form-control"'); ?>
				</div>
				<p class="help-block"><em><small>M&iacute;nimo 8 caracteres. Debe incluir may&uacute;sculas, min&uacute;sculas y n&uacute;meros.</small></em></p>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 <?= form_has_error_class('pwd_new2'); ?>">
				<label class="control-label" for="pwd_new2">{_login_input_new2_password_}</label>
				<div class="controls">
					<?= form_password('pwd_new2', '','maxlength="45" class="form-control"'); ?>
				</div>
			</div>

			<div class="control-group col-md-8 col-md-offset-2">
				<hr/>
			</div>

			<div class="control-group col-md-8 col-md-offset-2 col-xs-12">
				<div class="pull-right">
					<div class="controls">
						<button type="submit" name="btn_submit" class="btn btn-primary">
							<span class="fa fa-lock"></span> {_login_button_change_password_}
						</button>
					</div>
				</div>
			</div>
		<?= form_close(); ?>

	</div>

</div>
