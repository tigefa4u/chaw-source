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
	<?php echo $html->link('Commits', array('controller' => 'timeline', 'action' => 'index', 'type' => 'commits'));?>
	|
	<?php echo $html->link('Tickets', array('controller' => 'timeline', 'action' => 'index', 'type' => 'tickets'));?>
	|
	<?php echo $html->link('Comments', array('controller' => 'timeline', 'action' => 'index', 'type' => 'comments'));?>
	|
	<?php echo $html->link('Wiki', array('controller' => 'timeline', 'action' => 'index', 'type' => 'wiki'));?>
	|
	<?php echo $chaw->rss('Timeline Feed', $rssFeed);?>
</div>
<div class="timeline index">
	<table class="smooth">
	<?php $i = 0;
		foreach ((array)$timeline as $event):
			$zebra = ($i++ % 2 == 0) ? 'zebra' : null;
			$type = $event['Timeline']['model'];
			echo $this->element('timeline/' . strtolower($type), array('data' => $event, 'zebra' => $zebra));
		endforeach;
	?>
	</table>
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