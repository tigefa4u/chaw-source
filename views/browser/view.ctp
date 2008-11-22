<?php
$html->css('highlight/idea', null, null, false);

$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad();
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="browser view">
	<span class="history">
		<?php echo $html->link('history', array('controller' => 'commits', 'action' => 'history', $path));?>
	</span>

<?php
	echo $html->tag('pre', $html->tag('code', h($data['Content'])));
?>
</div>