<div class="users form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset>
 		<legend><?php __('Change Password');?></legend>
	<?php
		echo $form->input('password');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
