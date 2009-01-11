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

	<p class="description">
		<?php echo $project['Project']['description'];?>
	</p>

	<h4>Tickets</h4>
	<ul>
		<li>
			types: <?php echo $project['Project']['ticket_types'];?>
		</li>
		<li>
			priorities: <?php echo $project['Project']['ticket_priorities'];?>
		</li>
		<li>
			statuses: <?php echo $project['Project']['ticket_statuses'];?>
		</li>

	</ul>

</div>