<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Register');?></legend>
	<?php
		echo $form->input('username',array(
			'label'=> __('Username',true),
			'error' => array(
				'allowedChars' => __('Required: Minimum three (3) characters, letters (no accents), numbers and .-_ permitted..',true)
				'unique' => __('The project name must be unique.',true)
			)
		));
		echo $form->input('password', array(
			'label'=> __('Password',true)
		));
		echo $form->input('email', array('
			label'=> __('Email',true)
		));
	?>
	</fieldset>
<?php echo $form->end(__('Submit',true));?>
</div>
