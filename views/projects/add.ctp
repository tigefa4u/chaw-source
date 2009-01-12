<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php __('Project Setup') ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type');
		echo $form->input('name', array(
			'error' => array('unique' => 'The project name must be unique.')
		));
		echo $form->input('description');

		if (empty($this->passedArgs[0])) {
			echo $form->input('private');
		} else if ($this->passedArgs[0] == 'public'){
			echo $form->input('ohloh_project', array(
				'after' => '<small>the url for the project on <a href="http://ohloh.net">ohloh.net</a></small>'
			));
		}
	?>
	</fieldset>
	<fieldset class="options">
 		<legend>Options</legend>
	<?php
		echo $form->input('groups', array('type' => 'textarea'));
		echo $form->input('ticket_types', array('type' => 'textarea'));
		echo $form->input('ticket_priorities', array('type' => 'textarea'));
		echo $form->input('ticket_statuses', array('type' => 'textarea'));
	?>
	<p>Comma seperated</p>

	</fieldset>
<?php echo $form->end('Submit');?>
</div>