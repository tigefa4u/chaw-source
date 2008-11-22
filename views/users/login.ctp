<?php
	$session->flash('auth');
?>
<div class="users login form">
<?php echo $form->create(array('action' => 'login'));?>
	<fieldset>
 		<legend><?php __('Login');?></legend>
	<?php
		echo $form->input('username');
		echo $form->input('password');
		
		echo $form->input('remember_me', array(
			'type' => 'checkbox',
			'label' => 'Remember me for 2 weeks'
		));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>