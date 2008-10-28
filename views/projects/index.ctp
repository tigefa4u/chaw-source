<div class="projects index">
<?php foreach ((array)$projects as $project):?>

	<div class="project">

		<h3 class="name">
			<?php
				echo $html->link($project['Project']['name'], array(
					'admin' => false, 'project' => $project['Project']['url'],
					'controller' => 'wiki', 'action' => 'index',
				));?>
			<em>
				<?php
					echo $admin->link('admin', array('project' => $project['Project']['url'],
						'admin' => true, 'controller' => 'dashboard'
					));?>
			</em>
		</h3>

		<p class="description">
			<?php echo $project['Project']['description'];?>
		</p>

	</div>

<?php endforeach;?>
</div>
<div class="paging">
	<?php
		echo $paginator->prev();
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next();
	?>
</div>
<div class="actions">
	<?php echo $admin->link('New Project', array('admin' => true, 'controller' => 'projects', 'action' => 'add')); ?>
</div>