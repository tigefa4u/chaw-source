<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php __('Project Setup') ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type',array('label' => __('Repo Type',true)));
		echo $form->input('name', array(
			'error' => array(
				'minimum' => __('The project name must be at least 5 characters',true),
				'unique' => __('The project name must be unique.',true)
			)
		));
		echo $form->input('description');

		echo $form->hidden('private');

		if (!empty($this->passedArgs[0]) && $this->passedArgs[0] == 'public'){
			echo $form->input('ohloh_project', array(
				'label' => '<a href="https://www.ohloh.net">https://www.ohloh.net/</a>p/',
				'div' => 'inline'
			));
		}
		
		echo $form->submit('Submit');
	?>
	</fieldset>
	<fieldset class="options">
 		<legend><?php __('Groups')?></legend>
		<?php
			echo $form->input('config.groups', array('label' => false, 'type' => 'textarea'));
		?>
		<p><?php __('Comma seperated') ?></p>
	</fieldset>
	<fieldset class="options">
 		<legend><?php __('Tickets')?></legend>
		<?php
			echo $form->input('config.ticket.types', array('type' => 'textarea'));
			echo $form->input('config.ticket.priorities', array('type' => 'textarea'));
			echo $form->input('config.ticket.resolutions', array('type' => 'textarea'));
		?>
		<p><?php __('Comma seperated') ?></p>
	</fieldset>
<?php echo $form->end();?>
</div>