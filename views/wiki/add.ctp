<?php
$this->set('showdown', true);
$html->css('highlight/idea', null, null, false);
$javascript->link('highlight.pack', false);

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
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="wiki form">

	<div class="breadcrumbs">
		<h2><?php echo $chaw->breadcrumbs($path);?></h2>
	</div>

	<?php echo $form->create(array('url' => '/' . $this->params['url']['url']));?>

		<fieldset>
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
		</fieldset>
		<fieldset class="content">
			<?php
				echo '<div id="Preview" class="wiki-text"></div>';

				echo $form->input('content', array(
					'label' => false, 'after' => $html->tag('div', $this->element('markdown_help'), array('class' => 'help'))
				));
			?>
		</fieldset>

	<?php echo $form->end(__('Submit',true));?>

</div>
