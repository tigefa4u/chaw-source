<?php
	if (empty($projects)) {
		echo $html->tag('h2', 'Sorry, no projects are available');
	}
?>
<h2>
	Projects
</h2>
<div class="page-navigation">
	<?php echo $html->link('All', array('controller' => 'projects', 'action' => 'index', 'type' => 'both'));?>
	|
	<?php echo $html->link('Projects', array('controller' => 'projects', 'action' => 'index'));?>
	|
	<?php echo $html->link('Forks', array('controller' => 'projects', 'action' => 'index', 'type' => 'fork'));?>
	|
	<?php
		echo $html->link(
			$html->image('feed-icon.png', array(
				'width' => 14, 'height' => 14
			)),
			$rssFeed, array(
			'title' => 'Projects Feed', 'class' => 'rss', 'escape'=> false
		));?>
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
	<div class="project row">
		<?php echo $html->image(strtolower($project['Project']['repo_type']) . '.png', array('height' => 40, 'width' => 40)); ?>
		
		<h3 class="name">
			<?php
				echo $html->link($project['Project']['name'], array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'browser', 'action' => 'index',
				));?>

		</h3>

		<span class="description">
			<?php echo $project['Project']['description'];?>
		</span>

		<?php if (!empty($this->params['isAdmin'])): ?>
			<span class="admin">
				<?php
					echo ' | ';
					echo $html->link('view', array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'projects', 'action' => 'view',
					));
					echo ' | ';
					echo $html->link('edit', array(
						'admin' => true, 'project' => false, 'fork'=> $fork,
						'controller' => 'projects', 'action' => 'edit', $project['Project']['id']
					));
					echo ' | ';
					echo $html->link('admin', array(
						'admin' => true, 'project' => $url, 'fork'=> $fork,
						'controller' => 'dashboard'
					));
				?>
			</span>
		<?php endif;?>

		<span class="nav">
			<?php
				/*
				echo $html->link('source', array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'browser', 'action' => 'index',
				));
				echo ' | ';
				*/
				echo $html->link('timeline', array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'timeline', 'action' => 'index'
				));
				echo ' | ';
				echo $html->link('tickets', array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'tickets', 'action' => 'index'
				));
			?>
		</span>

	</div>

<?php endforeach;?>
</div>
<div class="paging">
	<?php
		$paginator->options(array('url'=> $this->passedArgs));
		echo $paginator->prev();
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next();
	?>
</div>