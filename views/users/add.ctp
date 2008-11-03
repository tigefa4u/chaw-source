<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Register');?></legend>
	<?php
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('email');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
