<?php
$html->css('highlight/idea', null, null, false);
$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad();

$(document).ready(function(){
	var text = jQuery.trim($("#WikiContent").val());
	if (text) {
		text = "<h3>Preview</h3>" + text;
	}
	$("#Preview").html(converter.makeHtml(text));
	$("#WikiContent").bind("keyup", function() {
		$("#Preview").html("<h3>Preview</h3>" + converter.makeHtml($(this).val()));
		hljs.initHighlighting.called = false;
		hljs.initHighlighting();
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="wiki form">

	<div class="breadcrumbs">
		<?php echo $chaw->breadcrumbs($path);?>
	</div>

	<?php echo $form->create(array('url' => '/' . $this->params['url']['url']));?>
		<fieldset>
			<legend><?php echo Inflector::humanize($slug); ?></legend>
		<?php
			echo $form->hidden('update');

			if ($form->value('slug')) {
				echo $form->hidden('slug');
			} else {
				echo $form->input('title', array('label' => 'Title'));
			}

			echo $form->input('path', array('div' => 'input text path',
				'between' => '<em>group pages</em>',
				'after' => "use paths to group pages into categories and subcategories. example: /blog/{$CurrentUser->username}",
			));

			echo $form->input('content', array('label' => 'Text'));

			/*if ($form->value('path')) {
				echo $form->input('path', array('div' => 'input text path',
					'between' => '<em>group pages</em>',
					'after' => "use paths to group pages into categories and subcategories. example: blog/{$CurrentUser->username}",
				));
			}*/
		?>

		<div id="Preview" class="preview"></div>

		</fieldset>


	<?php echo $form->end('Submit');?>

	<div class="help">
		<?php echo $this->element('markdown_help'); ?>
	</div>

</div>