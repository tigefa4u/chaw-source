<?php if (!empty($CurrentProject)):?>
<div class="project-details">
	<?php
		if(empty($branch)) {
			$branch = null;
		}
		if (empty($CurrentProject->approved)) {
			echo $html->tag('span', __('Awaiting Approval',true), array('class' => 'inactive'));
		}
	?>
	<p class="description">
		<strong><?php __('Description') ?>:</strong> <?php echo $CurrentProject->description;?>
	</p>

	<p class="path">
		<?php
			$remote = null;
			if ($CurrentProject->repo->type == 'git'):

				if (!empty($CurrentProject->fork)) {
					$remote = "forks/{$CurrentProject->fork}/";
				}

				echo '<strong>git clone</strong> ';
				echo "{$CurrentProject->remote->git}:$remote{$CurrentProject->url}.git";

				echo $html->tag('span', $html->link(__('view commits',true), $chaw->url((array)$CurrentProject, array(
					'admin' => false,
					'controller' => 'commits', 'action' => 'branch', $branch
				)), array('class' => 'history')));
				
				
				if ($this->action !== 'forks'):
					if (empty($this->params['fork'])):
						$link = $html->link(__('view forks',true), array(
							'admin' => false, 'fork' => false,
							'controller' => 'projects', 'action' => 'forks'
						), array('class' => 'detail'));
					else:
						$link = $html->link(__('view parent',true), array(
							'admin' => false, 'fork' => false,
							'controller' => 'source', 'action' => 'index'
						), array('class' => 'detail'));
					endif;
					echo $html->tag('span', $link);
				endif;

				if(!empty($this->params['isAdmin'])) {

					echo $html->tag('span', $html->link(__('rebase',true), array(
						'admin' => false,
						'controller' => 'repo', 'action' => 'rebase'
					), array('class' => 'detail')));

					if (!empty($CurrentProject->fork)):
						echo $html->tag('span', $html->link(__('delete',true), array(
							'admin' => false,
							'controller' => 'projects', 'action' => 'delete'
						), array('class' => 'detail')));

						echo $html->tag('span', $html->link(__('fast forward',true), array(
							'admin' => false,
							'controller' => 'repo', 'action' => 'fast_forward'
						), array('class' => 'detail')));
					endif;

				} else {
					
					if (empty($CurrentProject->fork) && !empty($CurrentUser->id)):
						echo $html->tag('span', $html->link(__('fork it',true), array(
							'admin' => false, 'fork' => false,
							'controller' => 'repo', 'action' => 'fork_it'
						), array('class' => 'detail')));
					endif;
					
				}

				if (!empty($this->params['isAdmin'])):
					echo $html->tag('span', $html->link(__('remove branch',true), array(
						'admin' => false,
						'controller' => 'source', 'action' => 'delete', $branch
					), array('class' => 'detail')));
				endif;

			else:
				echo '<strong>svn checkout</strong> ';
				echo "{$CurrentProject->remote->svn}/$remote{$CurrentProject->url}";

				echo $html->tag('span', $html->link(__('view commits',true), $chaw->url((array)$CurrentProject, array(
					'admin' => false,
					'controller' => 'commits', 'action' => 'index'
				)), array('class' => 'history')));

			endif;

			/*
			echo $html->tag('span', $html->link('download tar', array(
				'admin' => false,
				'controller' => 'projects', 'action' => 'index', 'ext' => 'tar'
			), array('class' => 'detail')));
			*/
		?>
	</p>
</div>
<?php endif;?>