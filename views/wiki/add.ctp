<?php
$html->css('highlight/idea', null, null, false);
$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad();

$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$("#Preview").html(converter.makeHtml(jQuery.trim($("#WikiContent").val())));
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

	<?php echo $form->create(array('url' => '/' . $this->params['url']['url']));?>
		<fieldset>
	 		<legend><?php echo $this->pageTitle; ?></legend>
		<?php
			if ($form->value('slug')) {
				echo $form->hidden('slug');
			} else {
				echo $form->input('title', array('label' => 'Title'));
			}

			echo $form->input('path', array('div' => 'input text path',
				'between' => '<em>group pages</em>',
				'after' => "use paths to group pages into categories and subcategories. example: blog/{$CurrentUser->username}",
			));

			echo $form->input('content', array('label' => 'Text'));

			/*if ($form->value('path')) {
				echo $form->input('path', array('div' => 'input text path',
					'between' => '<em>group pages</em>',
					'after' => "use paths to group pages into categories and subcategories. example: blog/{$CurrentUser->username}",
				));
			}*/
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