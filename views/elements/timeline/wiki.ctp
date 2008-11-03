<div class="wiki">

	<h3>
		Wiki: <?php echo $html->link($data['slug'], array('controller' => 'wiki', 'action' => 'view', $data['slug']));?>
	</h3>

	<p>
		<strong>Author:</strong> <?php echo $data['User']['username'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $data['created'];?>
	</p>

</div>