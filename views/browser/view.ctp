<?php
$html->css('highlight/idea', null, null, false);

$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad();
';
$javascript->codeBlock($script, array('inline' => false));
?>
<p class="history">
	<?php echo $html->link('history', array('controller' => 'commits', 'action' => 'history', $path));?>
</p>
<div class="browser view">
<?php
	echo $html->tag('pre', $html->tag('code', h($data['Content'])));
?>
</div>