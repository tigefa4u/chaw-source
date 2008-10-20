<?php
/*
For creole
$script = '
	function updateRender() {
		$("Html").innerHTML = toXHTML($("Text").value);
	}
	function installRenderer() {
		element = $("Text");
		element.onkeyup = element.onkeypress = element.ondrop = element.onchange = updateRender;
		updateRender();
	}

	window.onload = installRenderer;
';
*/
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$("#Html").html(converter.makeHtml($("#WikiContent").val()));
	$("#WikiContent").bind("keyup", function() {
		$("#Html").html(converter.makeHtml($(this).val()));
	});
	//$("#WikiContent").smartArea();
});
';
$javascript->codeBlock($script, array('inline' => false));

?>

<div class="wiki form">

	<?php echo $form->create(array('action' => 'edit'));?>
		<fieldset>
	 		<legend>Edit: <?php echo $this->pageTitle; ?></legend>
		<?php
			echo $form->hidden('id');
			echo $form->hidden('slug');
			echo $form->input('content', array('label' => 'Text'));
		?>
		</fieldset>

		<h3>Live Preview</h3>
		<div id="Html"></div>

	<?php echo $form->end('Submit');?>

	<div class="help">
		<?php echo $this->element('markdown_help'); ?>
	</div>

</div>