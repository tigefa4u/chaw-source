<?php
$html->css('highlight/idea', null, null, false);
$javascript->link('ghighlight.min', false);
?>
<div class="source view">
	<span class="history">
		<?php echo $html->link(__('history',true), array('controller' => 'commits', 'action' => 'history', $path));?>
	</span>

<?php
	echo $html->tag('pre', $html->tag('code', h($data['Content'])));
?>
</div>