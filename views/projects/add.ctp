<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php echo $this->pageTitle; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type',array('label' => array('labeltext' => __('Repo Type',true))));
		echo $form->input('name', array(
			'error' => array('unique' => __('The project name must be unique.',true))
		));
		echo $form->input('description');
		echo $form->input('private',array('label' => array('labeltext' => __('Private',true))));
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