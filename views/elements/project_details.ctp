<?php if (!empty($CurrentProject)):?>
<div class="project-details">
	<p class="description">
		<strong>Description:</strong> <?php echo $CurrentProject->description;?>
	</p>

	<p class="path">
		<?php
			if ($CurrentProject->repo->type == 'git'):
				echo '<strong>git clone</strong> ';
				echo "{$CurrentProject->remote->git}:{$CurrentProject->url}.git";
			else:
				echo '<strong>svn checkout</strong> ';
				echo "{$CurrentProject->remote->svn}/{$CurrentProject->url}";
			endif;
		?>
	</p>
</div>
<?php endif;?>