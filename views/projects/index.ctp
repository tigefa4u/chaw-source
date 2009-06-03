	<?php
	if (empty($projects)) {
		if ($this->action == 'forks') {
			echo $html->tag('h2', __('There are no forks',true));
		} else {
			echo $html->tag('h2', __('Sorry, no projects are available',true));
		}
		return;
	}
?>
<?php if ($this->action != 'forks'):?>

	<div class="page-navigation">
		<?php
			if (!empty($CurrentUser->Permission)) {
				echo $chaw->type(array('title' => __('Mine',true), 'type' => null)) . ' | ';
			}

			echo $chaw->type(array('title' => __('All',true),'type' =>'all'), array(
				'controller' => 'projects', 'action' => 'index',
			)) . ' | ';

			echo $chaw->type(array('title' => __('Public',true),'type' =>'public'), array(
				'controller' => 'projects', 'action' => 'index',
			)) . ' | ';

			echo $chaw->type(array('title' => __('Forks',true),'type' =>'forks'), array(
				'controller' => 'projects', 'action' => 'index',
			)) . ' | ';

			echo $html->link(
				$html->image('feed-icon.png', array(
					'width' => 14, 'height' => 14
				)),
				$rssFeed, array(
				'title' => __('Projects Feed',true), 'class' => 'rss', 'escape'=> false
			));?>
	</div>
<?php endif;?>

<?php if ($this->action == 'forks'):?>
	<h2>
		<?php __('Forks') ?>
	</h2>
	<?php echo $this->element('project_details'); ?>
<?php endif;?>

<div class="projects index">

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
					if (!empty($project['Project']['private'])) :
						echo $html->image('/css/images/lock.gif', array('height' => 20, 'width' => 20));
					endif;

					echo $html->link($project['Project']['name'], array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'source', 'action' => 'index',
					));

					?>
			</h3>
			<span class="nav">
				<?php
					if (!empty($this->params['isAdmin'])):
						//echo ' | ';
						echo $html->link(__('view',true), array(
							'admin' => false, 'project' => $url, 'fork'=> $fork,
							'controller' => 'projects', 'action' => 'view',
						));
						echo ' | ';
						echo $html->link(__('edit',true), array(
							'admin' => true, 'project' => false, 'fork'=> $fork,
							'controller' => 'projects', 'action' => 'edit', $project['Project']['id']
						));
						echo ' | ';
						echo $html->link(__('admin',true), array(
							'admin' => true, 'project' => $url, 'fork'=> $fork,
							'controller' => 'dashboard'
						));

					endif;

					if (!empty($CurrentUser->Permission) && empty($this->passedArgs['type']) && $this->action !== 'forks') {
						echo ' | ';
						echo $html->link('remove', array(
							'admin' => false,
							'controller' => 'projects', 'action' => 'remove',
							$project['Project']['id']
						));
					}

				?>
			</span>

			<p class="links">
				<?php
					echo $html->link(__('timeline',true), array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'timeline', 'action' => 'index'
					));
					echo ' | ';
					echo $html->link(__('wiki',true), array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'wiki', 'action' => 'index'
					));
					echo ' | ';
					echo $html->link(__('tickets',true), array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'tickets', 'action' => 'index'
					));
					echo ' | ';
					echo $html->link(__('versions',true), array(
						'admin' => false, 'project' => $url, 'fork'=> $fork,
						'controller' => 'versions', 'action' => 'index'
					));
				?>
			</p>

			<p class="description">
				<?php echo $project['Project']['description'];?>
			</p>

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