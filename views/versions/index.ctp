<?php $this->set('showdown', true); ?>
<h2>Versions</h2>
<?php if (!empty($this->params['isAdmin'])) { ?>
<div class="nav tabs right">
	<ul>
		<li><?php echo $chaw->admin(__('New Version',true), array('admin' => true, 'controller' => 'versions', 'action' => 'add'));?></li>
	</ul>
</div>
<?php } ?>

<div class="versions index">
	<ul>
	<?php foreach ((array)$versions as $version):?>

		<li class="version">
			<h3><?php echo $version['Version']['title'];?></h3>
			<div class="nav tabs right">
				<ul>
					<li><?php echo $chaw->admin('edit', array('admin' => true, 'controller' => 'versions', 'action' => 'edit', $version['Version']['id']));?></li>
					<li><p class="date">
						<?php if (empty($version['Version']['completed'])): ?>
							<strong><?php __('Due by') ?>:</strong> <?php echo $version['Version']['due_date'];?>
						<?php else:?>
							<strong><?php __('Completed') ?>:</strong> <?php echo date('Y-m-d', strtotime($version['Version']['due_date']));?>
						<?php endif; ?>

						</p>
					</li>
					
				</ul>
			</div>

			<p class="summary wiki-text">
				<?php echo $version['Version']['description'];?>
			</p>
			
		</li>

	<?php endforeach;?>
	</ul>
	<?php
		echo $paginator->prev('<< ' . __('previous', true));
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next(__('next', true) . ' >>');
	?>

</div>
