<?php
$this->set('showdown', true);
$script = '
$(document).ready(function(){
	$("#Preview").html(converter.makeHtml($("#VersionDescription").val()));
	$("#VersionDescription").bind("keyup", function() {
		$("#Preview").html("<h3>Preview</h3>" + converter.makeHtml($(this).val()));
	});
});
';
$html->scriptBlock($script, array('inline' => false));
?>
<div class="versions form">
<?php echo $form->create(array('action' => $this->action, 'url' => array('id' => false)));?>
	<fieldset class="main">
 		<legend><?php echo $title_for_layout;?></legend>
	<?php
		echo $form->input('id');

		if (!empty($projects)) :
			echo $form->input('project_id', array('label'=> __('Project',true)));
		endif;
	?>
	<?php echo $form->input('title', array('label'=> __('Title', true))); ?>
	<?php echo $form->input('description', array('label'=> false)); ?>
	<div class="help">
		<?php echo $this->element('markdown_help', array('short' => true)); ?>
	</div>
	<?php echo $form->input('due_date', array('label'=> __('Due Date',true))); ?>
	<?php echo $form->input('completed', array('label'=> __('Completed',true)) ); ?>

	<div id="Preview" class="preview wiki-text"></div>

	</fieldset>

	

<?php echo $form->end(__('Submit',true));?>
</div>
