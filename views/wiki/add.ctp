<div class="wiki form">
<?php echo $form->create();?>
	<fieldset>
 		<legend>Create <?php echo $this->pageTitle; ?></legend>
	<?php
		echo $form->hidden('slug');
		echo $form->input('content');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>