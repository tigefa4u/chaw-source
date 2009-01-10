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
	<?php
		$active = ($this->action == 'index') ? array('class' => 'active') : null;
		echo $html->link('Project', array(
			'controller' => 'timeline', 'action' => 'index',
		), $active) . ' | ';

		if (empty($CurrentProject->fork)) {
			$active = ($this->action == 'forks') ? array('class' => 'active') : null;
			echo $html->link('Forks', array('controller' => 'timeline', 'action' => 'forks'), $active) .' | ';
		} else {
			$active = ($this->action == 'parent') ? array('class' => 'active') : null;
			echo $html->link('Parent', array('controller' => 'timeline', 'action' => 'parent'), $active) .' | ';
		}

		echo $chaw->type('commits', array(
			'controller' => 'timeline',
		)) . ' | ';

		echo $chaw->type('tickets', array(
			'controller' => 'timeline',
		)) . ' | ';

		echo $chaw->type('comments', array(
			'controller' => 'timeline',
		)) . ' | ';

		echo $chaw->type('wiki', array(
			'controller' => 'timeline',
		)) . ' | ';

		echo $chaw->rss('Timeline Feed', $rssFeed);
	?>
</div>
<div class="timeline index">
	<?php $i = 0;
		foreach ((array)$timeline as $event):
			$zebra = ($i++ % 2 == 0) ? 'zebra' : null;
			$type = $event['Timeline']['model'];
			if (!empty($event[$type])) {
				echo $this->element('timeline/' . strtolower($type), array('label' => ucwords($type), 'data' => $event, 'zebra' => $zebra));
			}
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