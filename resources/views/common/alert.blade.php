@if (session('alert_message'))
<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<p>
		<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
		<strong>INFORMACION</strong>
	</p>

	<p>{{ session('alert_message') }}</p>
</div>
@endif