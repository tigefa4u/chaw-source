<?php
	echo $admin->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset>
 		<legend><?php echo $this->pageTitle; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type', array('disabled' => true));
		echo $form->input('name', array('disabled' => true));
		echo $form->hidden('url');
		echo $form->input('groups');
		echo $form->input('ticket_types');
		echo $form->input('ticket_priorities');
		echo $form->input('ticket_statuses');
		echo $form->input('description');
		echo $form->input('private');
		echo $form->input('active');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>