<div class="dashboard index">
	<h2><?php __('Dashboard');?></h2>

	<div class="panel">
		<?php echo $this->element('recent_tickets'); ?>
	</div>

	<div class="panel">
		<?php echo $this->element('recent_comments'); ?>
	</div>

	<div class="panel">
		<?php echo $this->element('recent_commits'); ?>
	</div>

	<div class="panel">
		<?php echo $this->element('recent_wiki'); ?>
	</div>


</div>
