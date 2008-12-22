<?php if (!empty($CurrentProject)):?>
<div class="project-details">
	<p class="description">
		<strong>Description:</strong> <?php echo $CurrentProject->description;?>
	</p>

	<p class="path">
		<?php
			$remote = null;
			if (!empty($CurrentProject->fork)) {
				$remote = "forks/{$CurrentProject->fork}/";
			}
			if ($CurrentProject->repo->type == 'git'):
				echo '<strong>git clone</strong> ';
				echo "{$CurrentProject->remote->git}:$remote{$CurrentProject->url}.git";

				if (empty($CurrentProject->fork) && !empty($CurrentUser->id)):
					echo $html->tag('span', $html->link('fork it', array(
						'admin' => false, 'fork' => false,
						'controller' => 'projects', 'action' => 'fork'
					), array('class' => 'detail')));
				endif;

				if ($this->action !== 'forks'):
					if (empty($this->params['fork'])):
						$link = $html->link('view forks', array(
							'admin' => false, 'fork' => false,
							'controller' => 'projects', 'action' => 'forks'
						), array('class' => 'detail'));
					else:
						$link = $html->link('view main project', array(
							'admin' => false, 'fork' => false,
							'controller' => 'browser', 'action' => 'index'
						), array('class' => 'detail'));
					endif;
					echo $html->tag('span', $link);
				endif;
			else:
				echo '<strong>svn checkout</strong> ';
				echo "{$CurrentProject->remote->svn}/$remote{$CurrentProject->url}";
			endif;

			/*
			echo $html->tag('span', $html->link('download tar', array(
				'admin' => false,
				'controller' => 'projects', 'action' => 'index', 'ext' => 'tar'
			), array('class' => 'detail')));
			*/

			echo $html->tag('span', $html->link('view history', array(
				'admin' => false,
				'controller' => 'commits', 'action' => 'index'
			), array('class' => 'history')));
		?>
	</p>
</div>
<?php endif;?>