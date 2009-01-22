<?php
$script = '
$(document).ready(function(){
	$("#Preview").html(converter.makeHtml($("#VersionDescription").val()));
	$("#VersionDescription").bind("keyup", function() {
		$("#Preview").html("<h3>Preview</h3>" + converter.makeHtml($(this).val()));
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="versions form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset class="main">
 		<legend><?php echo $this->pageTitle;?></legend>
	<?php		
		echo $form->input('title',array('label'=>array('labeltext' => __('Title',true))));
		echo $form->input('description',array('label'=>array('labeltext' => __('Description',true))));
		echo $form->input('due_date',array('label'=>array('labeltext' => __('Due Date',true))));
		echo $form->input('completed',array('label'=>array('labeltext' => __('Completed',true))));
	?>

	<div id="Preview"></div>

	</fieldset>

	<div class="help">
		<?php echo $this->element('markdown_help', array('short' => true)); ?>
	</div>

<?php echo $form->end(__('Submit',true));?>
</div>