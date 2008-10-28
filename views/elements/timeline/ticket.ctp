<div class="ticket">

	<h3>
		<?php echo Inflector::humanize($data['type']);?> Ticket: <?php echo $html->link($data['title'], array('controller' => 'tickets', 'action' => 'view', $data['id']));?>
	</h3>

	<p>
		<strong>Author:</strong> <?php echo $data['Reporter']['username'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $data['created'];?>
	</p>

</div>