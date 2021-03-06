<?php
$this->set('showdown', true);
$html->css('highlight/idea', null, array('inline' => false));
$html->script('highlight.pack', array('inline' => false));

$script = '
hljs.initHighlightingOnLoad();

$(document).ready(function(){
	$("#Preview").html(converter.makeHtml(jQuery.trim($("#TicketDescription").val())));
	$("#TicketDescription").bind("keyup", function() {
		$("#Preview").html(converter.makeHtml($(this).val()));
		hljs.initHighlighting.called = false;
		hljs.initHighlighting();
	});
});
';
$html->scriptBlock($script, array('inline' => false));
?>
<div class="tickets add form">
<?php echo $form->create('Ticket');?>
	<fieldset class="main">
 		<legend><?php __('Add Ticket');?></legend>

		<fieldset class="options">
		<?php
			echo $form->input('type');
			echo $form->input('priority');
			if (!empty($versions)) {
				echo $form->input('version_id');
			}
			if (!empty($this->params['isAdmin'])) {
				echo $form->input('owner', array('empty' => true));
			}
		?>
		</fieldset>

		<?php
			echo $form->input('title',array('label'=> __('Title', true)));
			echo $form->input('description', array(
				'value' => __("###What happened:\n- something\n\n\n###What was expected:\n- something else\n\n",true)
			));
		?>

		<div id="Preview" class="preview"></div>

	</fieldset>


	<fieldset class="options">
		<legend><?php __('Tags') ?></legend>
		<?php
			echo $form->textarea('tags');
		?>
		<? __('comma separated') ?>
	</fieldset>

	<div class="help">
		<?php echo $this->element('markdown_help', array('short' => true)); ?>
	</div>

	<?php echo $form->submit(__('Submit',true))?>


<?php echo $form->end();?>
</div>