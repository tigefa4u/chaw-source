<div class="permissions form">
<?php echo $form->create('Permission');?>
	<fieldset>
 		<legend><?php __('Add Permission');?></legend>
	<?php
		echo $form->input('user_id', array('after' => '  <strong>is a friendly</strong>'));
		echo $form->input('group');

		echo $form->input('fine_grained', array('type' => 'textarea'));

	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
