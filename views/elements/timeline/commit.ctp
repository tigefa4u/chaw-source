<div class="commit">

	<h3>
		Commit: <?php echo $chaw->commit($data['revision']);?>
	</h3>

	<p>
		<strong>Changed by:</strong>
		<?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['author'];?>
		<strong>on:</strong> <?php echo $time->nice($data['commit_date']);?>
	</p>

	<p>
		<strong>When:</strong> <?php echo $time->nice($data['commit_date']);?>
	</p>

	<p class="message">
		<?php echo $data['message'];?>
	</p>

	<?php
		$changes = unserialize($data['changes']);
		if(!empty($changes)):
	?>
		<p>
			<strong>Changes:</strong>
			<ul>
			<?php
				foreach ($changes as $changed) :
					echo $html->tag('li', $changed);
				endforeach;

			?>
			</ul>
		</p>
	<?php endif?>
</div>