<?php
	if (empty($projects)) {
		if ($this->action == 'forks') {
			echo $html->tag('h2', 'There are no forks');
		} else {
			echo $html->tag('h2', 'Sorry, no projects are available');
		}
		return;
	}
?>
<?php if (empty($this->params['project'])):?>

	<h2>
		Projects
	</h2>

	<div class="page-navigation">
		<?php
			echo $chaw->type(array('title' => 'Mine', 'type' => null)) . ' | ';

			echo $chaw->type('all', array(
				'controller' => 'projects', 'action' => 'index',
			)) . ' | ';

			echo $chaw->type('public', array(
				'controller' => 'projects', 'action' => 'index',
			)) . ' | ';

			echo $chaw->type('forks', array(
				'controller' => 'projects', 'action' => 'index',
			)) . ' | ';
		
			echo $html->link(
				$html->image('feed-icon.png', array(
					'width' => 14, 'height' => 14
				)),
				$rssFeed, array(
				'title' => 'Projects Feed', 'class' => 'rss', 'escape'=> false
			));?>
	</div>
<?php endif;?>

<div class="projects index">

	<?php if ($this->action == 'forks'):?>
		<h2>
			Forks
		</h2>
		<?php echo $this->element('project_details'); ?>
	<?php endif;?>

	<?php $i = 0;
		foreach ((array)$projects as $project):

			$zebra = ($i++ % 2 == 0) ? 'zebra' : null;

			$url = null;
			if ($project['Project']['id'] != 1) {
				$url = $project['Project']['url'];
			}
			$fork = null;
			if (!empty($project['Project']['fork'])) {
				$fork = $project['Project']['fork'];
			}
	?>
		<div class="project row <?php echo $zebra?>">
			<?php echo $html->image(strtolower($project['Project']['repo_type']) . '.png', array('height' => 40, 'width' => 40)); ?>

			<h3 class="name">
				<?php
					echo $html->link($project['Project']['name'], array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'browser', 'action' => 'index',
					));

					if (!empty($project['Project']['private'])) :
						echo $html->image('/css/images/lock.gif', array('height' => 20, 'width' => 20));
					endif;
					?>
			</h3>

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
					echo $html->link('wiki', array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'wiki', 'action' => 'index'
					));
					echo ' | ';
					echo $html->link('tickets', array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'tickets', 'action' => 'index'
					));

					if (!empty($this->params['isAdmin'])):
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
					endif;

				?>
			</span>

			<span class="description">
				<?php echo $project['Project']['description'];?>
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