<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Register');?></legend>
	<?php
		echo $form->input('username',array('label'=>array('labeltext'=>__('Username',true))));
		echo $form->input('password',array('label'=>array('labeltext'=>__('Password',true))));
		echo $form->input('email',array('label'=>array('labeltext'=>__('Email',true))));
	?>
	</fieldset>
<?php echo $form->end(__('Submit',true));?>
</div>
