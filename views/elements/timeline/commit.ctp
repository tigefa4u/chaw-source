<div class="commit row">

	<h3 class="name">
		Commit: <?php echo $chaw->commit($data['revision']);?>
	</h3>

	<span class="description">
		<?php echo $data['message'];?>
	</span>

	<span class="date">
		<?php echo $time->nice($data['commit_date']);?>
	</span>

	<span class="author">
		<?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['author'];?>
	</span>


</div>