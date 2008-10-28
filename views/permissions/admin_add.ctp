<div class="permissions form">
<?php echo $form->create('Permission');?>
	<fieldset>
 		<legend><?php __('Manage Permissions');?></legend>
		<?php
			echo $form->input('fine_grained', array('type' => 'textarea'));
		?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
