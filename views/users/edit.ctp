<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Update Info');?></legend>
	<?php
		echo $form->input('id');
		echo $form->hidden('username');
		echo $form->input('username', array('disabled' => true));
		echo $form->input('email');
		echo $form->input('ssh_key');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
