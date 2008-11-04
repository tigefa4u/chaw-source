<div class="ticket">

	<h3>
		<?php echo strtoupper(Inflector::humanize($data['type']));?> Ticket: 
		<?php echo $html->link($data['title'], array('controller' => 'tickets', 'action' => 'view', $data['id']));?>
	</h3>

	<p>
		<strong>Author:</strong> <?php echo $data['Reporter']['username'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $time->nice($data['created']);?>
	</p>

</div>