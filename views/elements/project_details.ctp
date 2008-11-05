<?php if (!empty($CurrentProject)):?>
<div class="project-details">
	<p class="description">
		<strong>Description:</strong> <?php echo $CurrentProject->description;?>
	</p>

	<p class="path">
		<?php
			if ($CurrentProject->repo->type == 'git'):
				echo '<strong>git clone</strong> ';
				echo "{$CurrentProject->remote}:{$CurrentProject->url}.git";
			endif;
		?>
	</p>
</div>
<?php endif;?>