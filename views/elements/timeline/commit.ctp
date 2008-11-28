<div class="commit row">

	<h3 class="name">
		Commit: <?php echo $chaw->commit($data['revision']);?>
	</h3>

	<span class="description">
		<?php echo $data['message'];?>
	</span>

<?php if (!empty($this->params['isAdmin'])):?>	
	<span class="admin">
		<?php echo $chaw->admin('delete', array('controller' => 'timeline', 'action' => 'delete', $event['Timeline']['id']));?>
	</span>
<?php endif;?>

	<span class="date">
		<?php echo $time->nice($data['commit_date']);?>
	</span>

	<span class="author">
		<?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['author'];?>
	</span>
	
</div>