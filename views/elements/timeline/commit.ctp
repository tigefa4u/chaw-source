<div class="commit row">
	
	<h3 class="subtitle">
		<?php echo $data['Commit']['branch'];?>
	</h3>
	
	<h3 class="name">
		Commit: <?php echo $chaw->commit($data['Commit']['revision']);?>
	</h3>

	<span class="description">
		<?php echo $text->truncate($data['Commit']['message'], 80, '...', false, true); ?>
	</span>

<?php if (!empty($this->params['isAdmin'])):?>
	<span class="admin">
		<?php echo $chaw->admin('remove', array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
	</span>
<?php endif;?>

	<span class="date">
		<?php echo $time->nice($data['Commit']['commit_date']);?>
	</span>

	<span class="author">
		<?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['Commit']['author'];?>
	</span>

</div>