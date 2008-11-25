<div class="ticket row">

	<h3 class="name">
		<?php echo strtoupper(Inflector::humanize($data['type']));?> Ticket: 
		<?php echo $html->link($data['title'], array('controller' => 'tickets', 'action' => 'view', $data['id']));?>
	</h3>
	
	<span class="description">
		created
	</span>	
	
	<span class="date">
		<?php echo $time->nice($data['created']);?>
	</span>

	<span class="author">
		<?php echo $data['Reporter']['username'];?>
	</span>

</div>