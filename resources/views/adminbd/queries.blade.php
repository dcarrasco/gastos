@extends('common.app_layout')

@section('modulo')
<div>
<?php if(count($queries_data)): ?>
	<table class="table table-striped table-hover table-condensed reporte">
		<thead>
			<tr>
				<th>SPID</th>
				<th>status</th>
				<th>login</th>
				<th>host name</th>
				<th>db name</th>
				<th>command</th>
				<th>program name</th>
				<th>start time</th>
				<th>duration</th>
				<th>CPU time</th>
				<th>Disk IO</th>
				<th>reads</th>
				<th>writes</th>
				<th>SQL</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($queries_data as $query): ?>
			<tr>
				<td><?= $query['SPID']; ?></td>
				<td><?= $query['Status']; ?></td>
				<td><?= $query['Login']; ?></td>
				<td><?= $query['HostName']; ?></td>
				<td><?= $query['DBName']; ?></td>
				<td><?= $query['Command']; ?></td>
				<td><?= $query['ProgramName']; ?></td>
				<td><?= $query['start_time']; ?></td>
				<td><?= fmtHora($query['total_elapsed_time']/1000); ?></td>
				<td><?= fmtHora($query['cpu_time']/1000); ?></td>
				<td><?= fmtCantidad($query['DiskIO']); ?></td>
				<td><?= fmtCantidad($query['reads']); ?></td>
				<td><?= fmtCantidad($query['writes']); ?></td>
				<td><?= $query['text']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

</div> <!-- fin content-module-main -->
@endsection
