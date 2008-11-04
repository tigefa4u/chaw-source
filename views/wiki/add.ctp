<?php
/*
$script = '
$(document).ready(function(){
	$("#Html").html(Wiky.toHtml($("#Text").val()));
});
';
*/
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$("#Preview").html(converter.makeHtml(jQuery.trim($("#WikiContent").val())));
	$("#WikiContent").bind("keyup", function() {
		$("#Preview").html("<h3>Preview</h3>" + converter.makeHtml($(this).val()));
	});
	//$("#WikiContent").smartArea();
});
';
$javascript->codeBlock($script, array('inline' => false));
?>

<div class="wiki form">

	<?php echo $form->create(array('url' => '/' . $this->params['url']['url']));?>
		<fieldset>
	 		<legend><?php echo $this->pageTitle; ?></legend>
		<?php
			if ($form->value('slug')) {
				echo $form->hidden('slug');
			} else {
				echo $form->input('slug', array('label' => 'Title'));
			}
			echo $form->input('content', array('label' => 'Text'));
		?>

			<div class="input">
				<span id="Preview"></span>
			</div>

		</fieldset>


	<?php echo $form->end('Submit');?>

	<div class="help">
		<?php echo $this->element('markdown_help'); ?>
	</div>

</div>