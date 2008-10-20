<div class="project">
	
	<h3 class="name">
		<?php echo $html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'view', 'project' => $project['Project']['url']));?>
	</h3>
	
	<p class="name">
		<?php echo $project['Project']['description'];?>
	</p>
	
</div>