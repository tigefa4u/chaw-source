<div class="ticket row">

	<h3 class="name">
		<?php echo strtoupper(Inflector::humanize($data['Ticket']['type']));?> Ticket:
		<?php echo $html->link($data['Ticket']['title'], array('controller' => 'tickets', 'action' => 'view', $data['Ticket']['number']));?>
	</h3>

	<span class="description">
		created
	</span>

	<span class="date">
		<?php echo $time->nice($data['Ticket']['created']);?>
	</span>

	<span class="author">
		<?php echo $data['Reporter']['username'];?>
	</span>

</div>