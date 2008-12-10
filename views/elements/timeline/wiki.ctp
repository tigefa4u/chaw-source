<div class="wiki row">

	<h3 class="name">
		Wiki: <?php echo $html->link($data['Wiki']['slug'], array('controller' => 'wiki', 'action' => 'index', $data['Wiki']['slug']));?>
	</h3>

	<span class="description">
		<?php
			if ($data['Wiki']['created'] != $data['Wiki']['modified']):
				echo 'modifed';
			else :
				echo 'created';
			endif;
		?>
	</span>
	
<?php if (!empty($this->params['isAdmin'])):?>
	<span class="admin">
		<?php echo $chaw->admin('remove', array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
	</span>
<?php endif;?>

	<span class="date">
		<?php echo $time->nice($data['Wiki']['created']);?>
	</span>

	<span class="author">
		<?php echo @$data['User']['username'];?>
	</span>

</div>