<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
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
	<?php
		echo $html->link(
			$html->image('feed-icon.png', array(
				'width' => 14, 'height' => 14
			)),
			$rssFeed, array(
			'title' => 'Timeline Feed', 'class' => 'rss', 'escape'=> false
		));?>
</div>
<div class="timeline index">
	<table class="smooth">
	<?php
		foreach ((array)$timeline as $event):
			$type = $event['Timeline']['model'];
			echo $this->element('timeline/' . strtolower($type), array('data' => $event));
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