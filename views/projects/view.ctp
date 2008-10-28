<div class="project">
	
	<h3 class="name">
		<?php echo $html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'view', 'project' => $project['Project']['url']));?>
	</h3>
	
	<p class="description">
		<?php echo $project['Project']['description'];?>
	</p>
	
	<p class="ticket-types">
		Ticket Types: <?php echo $project['Project']['ticket_types'];?>
	</p>
	
	<p class="ticket-priorities">
		Ticket Priorites: <?php echo $project['Project']['ticket_priorities'];?>
	</p>
	
	<p class="ticket-statuses">
		Ticket Statuses: <?php echo $project['Project']['ticket_statuses'];?>
	</p>
	
</div>