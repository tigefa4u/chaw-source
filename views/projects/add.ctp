<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php __('Project Setup') ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type',array('label' => array('labeltext' => __('Repo Type',true))));
		echo $form->input('name', array(
			'error' => array(
				'minimum' => __('The project name must be at least 5 characters',true)
				'unique' => __('The project name must be unique.',true)
			)
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
 		<legend><?php  __('Options') ?></legend>
	<?php
		echo $form->input('groups', array('type' => 'textarea','label' => array('labeltext' => __('Groups',true))));
		echo $form->input('ticket_types', array('type' => 'textarea','label' => array('labeltext' => __('Ticket Types',true))));
		echo $form->input('ticket_priorities', array('type' => 'textarea','label' => array('labeltext' => __('Ticket Priorities',true))));
		echo $form->input('ticket_statuses', array('type' => 'textarea','label' => array('labeltext' => __('Ticket Statuses',true))));
	?>
	<p><?php echo __('Comma seperated') ?></p>

	</fieldset>
<?php echo $form->end('Submit');?>
</div>