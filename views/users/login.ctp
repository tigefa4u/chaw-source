<?php
	$session->flash('auth');
?>
<div class="users login form">
<?php echo $form->create(array('action' => 'login'));?>
	<fieldset>
 		<legend><?php __('Login');?></legend>
	<?php
		echo $form->input('username',array('label'=>array('labeltext'=>__('Username',true))));
		echo $form->input('password',array('label'=>array('labeltext'=>__('Password',true))));
		
		echo $form->input(__('Remember Me',true), array(
			'type' => 'checkbox',
			'label' => __('Remember me for 2 weeks',true)
		));
	?>
	</fieldset>
<?php echo $form->end(__('Submit',true));?>
</div>