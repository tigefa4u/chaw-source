<div class="commit">

	<h3>
		Commit: <?php echo $html->link($data['revision'], array('controller' => 'commits', 'action' => 'view', $data['revision']));?>
	</h3>

	<p>
		<strong>Author:</strong> <?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['author'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $data['commit_date'];?>
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