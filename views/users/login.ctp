<?php
	$this->Session->flash('auth');
?>
<div class="users login form">
<?php echo $form->create(array('action' => 'login'));?>
	<fieldset>
 		<legend><?php __('Login');?></legend>
	<?php
		echo $form->input('username',array('label' => __('Username',true)));
		echo $form->input('password',array('label' => __('Password',true)));
		echo $form->input('remember_me', array(
			'type' => 'checkbox',
			'checked' => true,
			'label' => __('Remember me for 2 weeks', true)
		));
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>