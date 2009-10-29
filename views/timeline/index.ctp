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
<div class="nav tabs">
	<ul>
		<li class="project <?php echo ($this->action == 'index') ? 'active' : null; ?>"><?php
		echo $html->link(__('Project',true), array(
			'controller' => 'timeline', 'action' => 'index',
		)); ?></li>
		<li class="forks <?php echo (in_array($this->action, array('forks', 'parent'))) ? 'active' : null; ?>"><?php
		if (empty($CurrentProject->fork)) {
			echo $html->link(__('Forks',true), array('controller' => 'timeline', 'action' => 'forks'));
		} else {
			echo $html->link(__('Parent',true), array('controller' => 'timeline', 'action' => 'parent'), $active);
		}?></li>
		<li class="commits"><?php
		echo $chaw->type(array('title' => __('Commits',true),'type' =>'commits'), array(
			'controller' => 'timeline',
		)); ?></li>
		<li class="tickest"><?php
		echo $chaw->type(array('title' => __('Tickets',true),'type' =>'tickets'), array(
			'controller' => 'timeline',
		)); ?></li>
		<li class="comments"><?php
		echo $chaw->type(array('title' => __('Comments',true),'type' =>'comments'), array(
			'controller' => 'timeline',
		)); ?></li>
		<li class="wiki"><?php
		echo $chaw->type(array('title' => __('Wiki',true),'type' =>'wiki'), array(
			'controller' => 'timeline',
		)); ?></li>
		<li class="rss"><?php echo $chaw->rss('Timeline Feed', $rssFeed); ?></li>
	</ul>
</div>

<div class="timeline index">
	<ul>
	<?php $i = 0; $prevDate = null;
		foreach ((array)$timeline as $event):
			$zebra = ($i++ % 2 == 0) ? 'zebra' : null;
			$type = $event['Timeline']['model'];
			$date = $event['Timeline']['created'];
			$currentDate = date('l F d', strtotime($date));
			if (!empty($event[$type])) {
				if ($currentDate !== $prevDate)  {
					if ($i > 1 ) {
						echo "</ul></li>";
					}
					echo "<li><p class=\"the-date\">{$currentDate}</p>";
					echo "<ul>";
				}
				echo $this->element('timeline/' . strtolower($type), array('label' => ucwords($type), 'data' => $event, 'zebra' => $zebra));
			}
			$prevDate = $currentDate;
		endforeach;
	?>
	</ul>
</div>
<div class="paging">
	<?php
		$paginator->options(array('url'=> $this->passedArgs));

		echo $paginator->prev('<< ' . __('previous', true));

		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));

		echo $paginator->next(__('next', true) . ' >>');
	?>
</div>
