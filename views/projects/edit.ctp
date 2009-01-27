<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action,
		'url' => array('id' => false)
));?>
	<fieldset class="main">
 		<legend><?php echo $this->pageTitle; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type', array('disabled' => true,'label' => array('labeltext' => __('Repo Type',true))));
		echo $form->input('name', array('disabled' => true));
		echo $form->hidden('url');
		echo $form->hidden('fork');
		echo $form->input('description');
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
	<p><?php __('Comma seperated') ?></p>

	</fieldset>
<?php echo $form->end('Submit');?>
</div>