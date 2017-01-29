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
				<td><?= fmt_hora($query['total_elapsed_time']/1000); ?></td>
				<td><?= fmt_hora($query['cpu_time']/1000); ?></td>
				<td><?= fmt_cantidad($query['DiskIO']); ?></td>
				<td><?= fmt_cantidad($query['reads']); ?></td>
				<td><?= fmt_cantidad($query['writes']); ?></td>
				<td><?= $query['text']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

</div> <!-- fin content-module-main -->
