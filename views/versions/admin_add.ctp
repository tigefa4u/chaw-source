<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
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
		echo $form->input('title');
		echo $form->input('description');
		echo $form->input('due_date');
		echo $form->input('completed');
	?>

	<div id="Preview"></div>

	</fieldset>

	<div class="help">
		<?php echo $this->element('markdown_help', array('short' => true)); ?>
	</div>

<?php echo $form->end('Submit');?>
</div>