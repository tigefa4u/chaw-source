<div class="project">

	<h2 class="name">
		<?php echo $html->link($project['Project']['name'], array(
			'project' => $project['Project']['url'],
			'controller' => 'projects', 'action' => 'view'
		));?>
	</h2>
	<?php
		if (empty($project['Project']['approved'])) {
			echo $html->tag('span', 'Awaiting Approval', array('class' => 'inactive'));
		}
	?>
	<?php
		if (!empty($project['Project']['private'])) {
			echo $html->tag('span', 'Private', array('class' => 'active'));
		}
	?>
	
	<p class="description">
		<?php echo $project['Project']['description'];?>
	</p>
	
	<h4>Groups</h4>
	<?php echo $project['Project']['config']['groups'];?>
	
	<h4>Tickets</h4>
	<ul>
		<li>
			types: <?php echo $project['Project']['config']['ticket']['types'];?>
		</li>
		<li>
			priorities: <?php echo $project['Project']['config']['ticket']['priorities'];?>
		</li>
		<li>
			statuses: <?php echo $project['Project']['config']['ticket']['resolutions'];?>
		</li>

	</ul>

</div>