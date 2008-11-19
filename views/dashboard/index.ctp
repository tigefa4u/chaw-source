<div class="dashboard index">
	<h2><?php __('Dashboard');?></h2>

	<div class="panels">
		<?php echo $this->element('current_projects'); ?>
		
		<?php echo $this->element('recent/commits'); ?>

		<?php echo $this->element('recent/tickets'); ?>

		<?php echo $this->element('recent/comments'); ?>

		<?php echo $this->element('recent/wiki'); ?>
	</div>

</div>
