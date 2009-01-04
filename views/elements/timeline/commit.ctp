<div class="commit row <?php echo $zebra;?>">
	<h3 class="name">
		<?php echo (isset($label)) ? $label . ': ' : null;?>
		<?php
			echo $chaw->commit($data['Commit']['revision']);

			$project = null;
			if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
				$project = ' in '. $html->link($data['Project']['name'], $chaw->url($data['Project'], array(
					'admin' => false, 'controller' => 'browser'
				)), array('class' => 'project'));
			}
			echo $project;
		?>		
	</h3>
	
	<?php if (!empty($this->params['isAdmin'])):?>
		<span class="admin">
			<?php echo (empty($CurrentProject->fork) && $project) ? $chaw->admin('merge', array('controller' => 'projects', 'action' => 'merge', $data['Project']['fork'])) . ' | ': null;?>
			<?php echo $chaw->admin('remove', array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
		</span>
	<?php endif;?>

	<span class="subtitle">
		<?php echo $data['Commit']['branch'];?>
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