<?php
$html->css('highlight/dark', null, array('inline' => false));
$html->script('ghighlight.min', array('inline' => false));
?>
<div class="source view">
	<span class="history"><?php

		if (!empty($branch)) {
			$path = "branches/{$branch}/{$path}";
		}

		echo $html->link(__('history',true), $chaw->url((array) $CurrentProject, array(
				'controller' => 'commits', 'action' => 'history', $path
		)));
	?></span>

<?php
	echo $html->tag('pre', $html->tag('code', h($data['Content'])));
?>
</div>