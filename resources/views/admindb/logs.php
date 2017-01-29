<div>
<pre>
<?php if(count($log_file)): ?>
<?php foreach($log_file as $linea): ?>
<?= htmlspecialchars($linea); ?>
<?php endforeach; ?>
<?php endif; ?>
</pre>
</div> <!-- fin content-module-main -->
