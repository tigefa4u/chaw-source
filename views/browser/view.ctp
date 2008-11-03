<?php
$html->css('highlight/idea', null, null, false);

$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad();
';
$javascript->codeBlock($script, array('inline' => false));
?>

<div class="browser view">
<?php 	
	echo $html->tag('pre', $html->tag('code', $data['Content']));
?>
</div>