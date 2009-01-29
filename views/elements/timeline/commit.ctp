<div class="commit row <?php echo $zebra;?>">
	<h3 class="name">
		<?php echo (isset($label)) ? $label . ': ' : null;?>
		<?php
			echo $chaw->commit($data['Commit']['revision'], $data['Project']);

			$project = null;
			if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
				$project = ' in '.
					$html->link($data['Project']['name'], $chaw->url($data['Project'], array(
						'admin' => false, 'controller' => 'source'
					)), array('class' => 'project'));
			}
			echo $project;
		?>
	</h3>

	<?php if (!empty($this->params['isAdmin'])):?>
		<span class="admin">
			<?php
			 	echo (empty($CurrentProject->fork) && $project) ?
					$chaw->admin('merge', array(
						'controller' => 'repo', 'action' => 'merge', $data['Project']['fork']
					)) . ' | '
				: null;

				if ($this->name == 'Timeline') {
					echo $chaw->admin(__('remove',true), array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));
				} else if ($this->name == 'Commits') {
					echo $chaw->admin(__('remove',true), array('controller' => 'commits', 'action' => 'remove', $data['Commit']['id']));
				}
			?>
		</span>
	<?php endif;?>

	<span class="subtitle">
		<?php //echo $data['Commit']['branch'];?>
	</span>

	<span class="subtitle">
		<?php echo $html->link($data['Branch']['name'], array(
				'controller' => 'source', 'action' => 'branches',
				$data['Branch']['name']
			));?>
	</span>

	<span class="description footer">
		<?php echo $text->truncate($data['Commit']['message'], 80, '...', false, true); ?>
	</span>

	<span class="date footer">
		<?php echo $time->nice($data['Commit']['commit_date']);?>
	</span>

	<span class="author footer">
		<?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['Commit']['author'];?>
	</span>

</div>
<?php


?>