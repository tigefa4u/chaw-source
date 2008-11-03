<div class="versions form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php echo $this->pageTitle;?></legend>
	<?php
		echo $form->input('title');
		echo $form->input('description');
		echo $form->input('due_date');
		echo $form->input('completed');
	?>
	</fieldset>

	<div class="help">
		<?php echo $this->element('markdown_help'); ?>
	</div>

<?php echo $form->end('Submit');?>
</div>