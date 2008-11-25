<div class="wiki row">

	<h3 class="name">
		Wiki: <?php echo $html->link($data['slug'], array('controller' => 'wiki', 'action' => 'index', $data['slug']));?>
	</h3>

	<span class="description">
		<?php
			if ($data['created'] != $data['modified']):
				echo 'modifed';
			else :
				echo 'created';
			endif;
		?>
	</span>

	<span class="date">
		<?php echo $time->nice($data['created']);?>
	</span>

	<span class="author">
		<?php echo @$data['User']['username'];?>
	</span>

</div>