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
			else:
				echo '<strong>svn checkout</strong> ';
				echo "{$CurrentProject->remote->svn}/$remote{$CurrentProject->url}";
			endif;

			if (empty($CurrentProject->fork)):
				echo $html->link('fork', array(
					'fork' => false,
					'controller' => 'projects', 'action' => 'fork'
				), array('class' => 'detail'));
			endif;
		?>
	</p>
</div>
<?php endif;?>