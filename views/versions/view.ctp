<?php
$this->set('showdown', true);
?>
<div class="version view">
	<h3>
		<?php echo $version['Version']['title'];?>
		<em>
			<?php echo $chaw->admin('edit', array('admin' => true, 'controller' => 'versions', 'action' => 'edit', $version['Version']['id']));?>
		</em>
	</h3>

	<p class="date">
		<?php if (empty($version['Version']['completed'])): ?>
			<strong><?php __('Due by') ?>:</strong> <?php echo $version['Version']['due_date'];?>
		<?php else:?>
			<strong><?php __('Completed') ?>:</strong> <?php echo date('Y-m-d', strtotime($version['Version']['due_date']));?>
		<?php endif; ?>
	</p>

	<p class="summary wiki-text">
		<?php echo $version['Version']['description'];?>
	</p>

</div>