<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php echo $this->pageTitle; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type');
		echo $form->input('name', array(
			'error' => array(
				'minimum' => __('The project name must be at least 5 characters',true),
				'unique' => __('The project name must be unique.',true)
			)
		));
		echo $form->input('description');
		echo $form->input('private');
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
<?php echo $form->end('Submit');?>
</div>