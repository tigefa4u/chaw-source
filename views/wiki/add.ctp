<?php
$this->set('showdown', true);
$html->script('highlight.pack', array('inline' => false));

$script = '
hljs.initHighlightingOnLoad();

$(document).ready(function(){
	var text = jQuery.trim($("#WikiContent").val());
	$("#Preview").html(converter.makeHtml(text));
	$("#WikiContent").bind("keyup", function() {
		$("#Preview").html(converter.makeHtml($(this).val()));
		hljs.initHighlighting.called = false;
		hljs.initHighlighting();
	});
});
';
$html->scriptBlock($script, array('inline' => false));
?>
<div class="wiki form">

	<?php echo $form->create(array('url' => '/' . $this->params['url']['url']));?>

		<fieldset>
			<legend>Wiki Document</legend>
		<?php

			echo $html->tag('div',$form->input('active') . $form->input('read_only'), array('class' => 'single'));

			echo $form->input('path', array('div' => 'input text path',
				'label' => "<small>" . sprintf(
					__("use a path to group pages into categories and subcategories. example: /logs/by/%s/", true),
					$CurrentUser->username
				) . "</small>",
			));

			if ($form->value('slug')) {
				echo $form->hidden('slug');
				echo $form->input('slug', array('label' => false));
			} else {
				echo $form->input('title', array('label' => false, 'value' => 'new-page'));
			}
		?>
			<?php
				echo $form->input('content', array(
					'label' => false, 'after' => $html->tag('div', $this->element('markdown_help'), array('class' => 'help'))
				));
			?>
		</fieldset>
		<fieldset>
			<legend>Preview</legend>
			<div id="Preview" class="preview wiki-text"></div>
		</fieldset>

	<?php echo $form->end(__('Submit',true));?>

</div>