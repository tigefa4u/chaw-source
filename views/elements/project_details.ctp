<?php if (!empty($CurrentProject)):?>
	<div class="description site-subtitle">
		<?php echo $CurrentProject->description;?>
	</div>
	<?php
		if (empty($branch)) {
			$branch = null;
		}
		if (empty($CurrentProject->approved)) {
			echo $html->tag('span', __('Awaiting Approval', true), array('class' => 'inactive status'));
		}
	?>
	<div class="path">
		<?php
			$remote = null;
			if ($CurrentProject->repo->type == 'git'):

				if (!empty($CurrentProject->fork)) {
					$remote = "forks/{$CurrentProject->fork}/";
				}

				echo '<strong>git clone</strong> ';
				echo "{$CurrentProject->remote->git}:$remote{$CurrentProject->url}.git";
			else:
				echo '<strong>svn checkout</strong> ';
				echo "{$CurrentProject->remote->svn}/$remote{$CurrentProject->url}";
			endif;
		?>
	</div>
<?php endif;?>