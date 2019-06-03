@if (session('alert_message'))
<div class="alert alert-info alert-dismissible fade show mt-3 shadow-sm" role="alert">
	<button type="button" class="close text-monospace" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

	<p>
		<span class="fa fa-info-circle" aria-hidden="true"></span>
		<strong>INFORMACION</strong>
	</p>

	<p>{{ session('alert_message') }}</p>
</div>
@endif
