<?php if (!empty($CurrentProject)):?>
<div class="nav tabs right">
<ul>
		<?php
			if (empty($branch)) {
				$branch = null;
			}
			$remote = null;
			if ($CurrentProject->repo->type == 'git'):
				echo $html->tag('li', $html->link(__('view commits',true), $chaw->url((array)$CurrentProject, array(
					'admin' => false,
					'controller' => 'commits', 'action' => 'branch', $branch
				)), array('class' => 'history')));


				if ($this->action !== 'forks'):
					if (empty($this->params['fork'])):
						$link = $html->link(__('view forks',true), array(
							'admin' => false, 'fork' => false,
							'controller' => 'projects', 'action' => 'forks'
						), array('class' => 'forks'));
					else:
						$link = $html->link(__('view parent',true), array(
							'admin' => false, 'fork' => false,
							'controller' => 'source', 'action' => 'index'
						), array('class' => 'parent'));
					endif;
					echo $html->tag('li', $link);
				endif;

				if(!empty($this->params['isAdmin'])) {

					echo $html->tag('li', $html->link(__('rebase',true), array(
						'admin' => false,
						'controller' => 'repo', 'action' => 'rebase'
					), array('class' => 'rebase')));

					if (!empty($CurrentProject->fork)):
						echo $html->tag('li', $html->link(__('delete',true), array(
							'admin' => false,
							'controller' => 'projects', 'action' => 'delete'
						), array('class' => 'delete')));

						echo $html->tag('li', $html->link(__('fast forward',true), array(
							'admin' => false,
							'controller' => 'repo', 'action' => 'fast_forward'
						), array('class' => 'fast-forward')));
					endif;

				} else {

					if (empty($CurrentProject->fork) && !empty($CurrentUser->id)):
						echo $html->tag('li', $html->link(__('fork it',true), array(
							'admin' => false, 'fork' => false,
							'controller' => 'repo', 'action' => 'fork_it'
						), array('class' => 'forkit')));
					endif;

				}

				if (!empty($this->params['isAdmin'])):
					echo $html->tag('li', $html->link(__('remove branch',true), array(
						'admin' => false,
						'controller' => 'source', 'action' => 'delete', $branch
					), array('class' => 'remove-branch')));
				endif;

			else:
				echo $html->tag('li', $html->link(__('view commits',true), $chaw->url((array)$CurrentProject, array(
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
</ul>
</div>
<?php endif;?>