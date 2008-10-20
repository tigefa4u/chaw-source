<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$("#Preview").html(converter.makeHtml($("#TicketDescription").val()));
	$("#TicketDescription").bind("keyup", function() {
		$("#Preview").html("<h3>Preview</h3>" + converter.makeHtml($(this).val()));
	});
	//$("#WikiContent").smartArea();
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="tickets add form">
<?php echo $form->create('Ticket');?>
	<fieldset class="main">
 		<legend><?php __('Add Ticket');?></legend>
		<?php
			echo $form->input('title');
			echo $form->input('description');
		?>
		
		<div class="input">
			<div id="Preview"></div>
		</div>
	
	</fieldset>


	<fieldset class="options">
		<legend>Tags</legend>
		<?php
			echo $form->textarea('tags');
		?>
		comma separated
	</fieldset>


	<fieldset class="options">
		<legend>Options</legend>
		<?php
			if (!empty($versions)) {
				echo $form->input('version_id');
			}
			echo $form->input('type');
			echo $form->input('priority');
		?>
	</fieldset>
	
	<div class="help">
		<?php echo $this->element('markdown_help'); ?>
	</div>

<?php echo $form->end('Submit');?>
</div>