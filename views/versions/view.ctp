<div class="versions view">
	<h3>
		<?php echo $version['Version']['title'];?>
	</h3>

	<p>
		<?php echo $chaw->admin('edit', array('admin' => true, 'controller' => 'versions', 'action' => 'edit', $version['Version']['id']));?>
	</p>

	<p class="summary">
		<?php echo $version['Version']['description'];?>
	</p>

	<p class="created">
		<strong>Created:</strong> <?php echo date('Y-m-d', strtotime($version['Version']['created']));?>
	</p>

	<p class="created">
		<strong>Due by:</strong> <?php echo $version['Version']['due_date'];?>
	</p>
</div>