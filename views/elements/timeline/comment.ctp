<div class="comment">

	<h3>
		Comment by 	<em><?php echo $data['User']['username'];?></em>
	</h3>
	<p>
		<strong><?php echo Inflector::humanize($data['Ticket']['type']);?> Ticket:</strong> <?php echo $html->link($data['Ticket']['title'], array('controller' => 'tickets', 'action' => 'view', $data['Ticket']['id']));?>
	</p>
	<p>
		<strong>Date:</strong> <?php echo $data['created'];?>
	</p>

</div>