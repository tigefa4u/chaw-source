<?php
	if (empty($projects)) {
		echo $html->tag('h2', 'Sorry, no projects are available');
	}
?>
<div class="page-navigation">
	<?php echo $html->link('All', array('controller' => 'projects', 'action' => 'index', 'type' => 'both'));?>
	|
	<?php echo $html->link('Projects', array('controller' => 'projects', 'action' => 'index'));?>
	|
	<?php echo $html->link('Forks', array('controller' => 'projects', 'action' => 'index', 'type' => 'fork'));?>
</div>
<div class="projects index">
<?php foreach ((array)$projects as $project):

		$url = null;
		if ($project['Project']['id'] != 1) {
			$url = $project['Project']['url'];
		}
		$fork = null;
		if (!empty($project['Project']['fork'])) {
			$fork = $project['Project']['fork'];
		}
?>
	<div class="project">

		<h3 class="name">
			<?php
				echo $html->link($project['Project']['name'], array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'browser', 'action' => 'index',
				));?>

		</h3>

		<?php if (!empty($this->params['isAdmin'])): ?>
			<p>
				<?php
					echo $html->link('view', array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'projects', 'action' => 'view',
					));
					echo ' | ';
					echo $html->link('edit', array(
						'admin' => true, 'project' => $url, 'fork'=> $fork,
						'controller' => 'projects', 'action' => 'edit'
					));
					echo ' | ';
					echo $html->link('admin', array(
						'admin' => true, 'project' => $url, 'fork'=> $fork,
						'controller' => 'dashboard'
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