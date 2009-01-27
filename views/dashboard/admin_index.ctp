<div class="dashboard index">
	<h2><?php __('Dashboard');?></h2>

	<?php echo $this->element('project_details'); ?>

	<div class="panels">
		<?php echo $this->element('recent/commits'); ?>

		<?php echo $this->element('recent/commits', array('commits' => $forkCommits, 'title' => 'Commits in Forks')); ?>

		<?php echo $this->element('recent/tickets'); ?>

		<?php echo $this->element('recent/comments'); ?>

		<?php echo $this->element('recent/wiki'); ?>
	</div>
</div>
