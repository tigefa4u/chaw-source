<?php
	if (empty($projects)) {
		echo $html->tag('h2', 'Sorry, no projects are available');
	}
?>
<div class="projects index">
<?php foreach ((array)$projects as $project):

		$url = null;
		if ($project['Project']['id'] != 1) {
			$url = $project['Project']['url'];
		}
?>
	<div class="project">

		<h3 class="name">
			<?php
				echo $html->link($project['Project']['name'], array(
					'admin' => false, 'project' => $url,
					'controller' => 'wiki', 'action' => 'index',
				));?>

		</h3>

		<?php if (!empty($this->params['isAdmin'])): ?>
			<p>
				<?php
					echo $html->link('view', array(
						'admin' => false, 'project' => $url,
						'controller' => 'projects', 'action' => 'view',
					));
					echo ' | ';
					echo $html->link('edit', array('project' => $url,
						'admin' => true, 'controller' => 'projects', 'action' => 'edit'
					));
					echo ' | ';
					echo $html->link('admin', array('project' => $url,
						'admin' => true, 'controller' => 'dashboard'
					));
				?>
			</p>
		<?php endif;?>

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