<div class="comment row <?php echo $zebra;?>">

	<h3 class="name">
		<?php echo strtoupper(Inflector::humanize($data['Ticket']['type']));?> Ticket:
		<?php echo $html->link($data['Ticket']['title'], array(
			'controller' => 'tickets', 'action' => 'view', $data['Ticket']['number'],
			'#' => 'c' . $data['Comment']['id']
		));?>
	</h3>

	<span class="description">
		<?php echo $text->truncate($data['Comment']['body'], 80, '...', false, true); ?>
	</span>

<?php if (!empty($this->params['isAdmin'])):?>
	<span class="admin">
		<?php echo $chaw->admin('remove', array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
	</span>
<?php endif;?>

	<span class="date">
		<?php echo $time->nice($data['Comment']['created']);?>
	</span>

	<span class="author">
		<?php echo $data['User']['username']; ?>
	</span>

</div>