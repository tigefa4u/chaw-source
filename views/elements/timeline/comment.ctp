<div class="comment">

	<h3>
		Comment:
	</h3>
	<p>
		<strong>Ticket:</strong> <?php echo $html->link($data['Ticket']['title'], array('controller' => 'tickets', 'action' => 'view', $data['id']));?>
	</p>
	<p>
		<strong>Author:</strong> <?php echo $data['User']['username'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $data['created'];?>
	</p>

</div>