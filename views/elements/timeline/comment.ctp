<div class="comment">

	<h3>
		Comment by 	<?php echo  $html->link($data['User']['username'], array('controller' => 'tickets', 'action' => 'view', $data['Ticket']['id']));?>
	</h3>
	<p>
		<strong><?php echo strtoupper(Inflector::humanize($data['Ticket']['type']));?> Ticket:</strong>
		<?php echo $html->link($data['Ticket']['title'], array('controller' => 'tickets', 'action' => 'view', $data['Ticket']['id']));?>
	</p>
	<p>
		<strong>Date:</strong> <?php echo $time->nice($data['created']);?>
	</p>

</div>