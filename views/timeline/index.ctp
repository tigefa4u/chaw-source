<?php
$script = '
$(document).ready(function(){
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<h2>
	Timeline
</h2>
<div class="page-navigation">
	<?php echo $html->link('All', array('controller' => 'timeline', 'action' => 'index'));?>
	|
	<?php echo $html->link('Forks', array('controller' => 'timeline', 'action' => 'forks'));?>
	|
	<?php echo $html->link('Commits', array('controller' => 'timeline', 'type' => 'commits'));?>
	|
	<?php echo $html->link('Tickets', array('controller' => 'timeline', 'type' => 'tickets'));?>
	|
	<?php echo $html->link('Comments', array('controller' => 'timeline', 'type' => 'comments'));?>
	|
	<?php echo $html->link('Wiki', array('controller' => 'timeline', 'action' => 'index', 'type' => 'wiki'));?>
	|
	<?php echo $chaw->rss('Timeline Feed', $rssFeed);?>
</div>
<div class="timeline index">
	<?php $i = 0;
		foreach ((array)$timeline as $event):
			$zebra = ($i++ % 2 == 0) ? 'zebra' : null;
			$type = $event['Timeline']['model'];
			echo $this->element('timeline/' . strtolower($type), array('label' => ucwords($type), 'data' => $event, 'zebra' => $zebra));
		endforeach;
	?>
</div>
<div class="paging">
	<?php
		$paginator->options(array('url'=> $this->passedArgs));

		echo $paginator->prev();

		echo $paginator->numbers(array(
			'before' => ' | ', 'after' => ' | '
		));

		echo $paginator->next();
	?>
</div>