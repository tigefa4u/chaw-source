<h2><?php __('Dashboard');?></h2>

<?php

if (empty($timeline)) {
	echo $html->link(__('My Account',true), array('controller' => 'users', 'action' => 'account'));
	return;
}
?>

<div class="nav tabs">
	<ul>
	<li><?php echo $html->link(__('My Account',true), array('controller' => 'users', 'action' => 'account')); ?></li>
	<li><?php echo $html->link(__('My Projects', true), array('controller' => 'projects', 'action' => 'index')); ?></li>
	<li><?php echo $chaw->type(array('title' => __('Commits',true),'type' =>'commits'), array(
			'controller' => 'dashboard',
		)); ?></li>
	<li><?php echo $chaw->type(array('title' => __('Tickets',true),'type' =>'tickets'), array(
			'controller' => 'dashboard',
		)); ?></li>
	<li><?php echo $chaw->type(array('title' => __('Comments',true),'type' =>'comments'), array(
			'controller' => 'dashboard',
		)); ?></li>
	<li><?php echo $chaw->type(array('title' => __('Wiki',true),'type' =>'wiki'), array(
			'controller' => 'dashboard',
		)); ?></li>
	<li><?php echo $html->link(
			$html->image('feed-icon.png', array(
				'width' => 14, 'height' => 14
			)),
			$rssFeed, array(
			'title' => "{$CurrentUser->username}'s Feed", 'class' => 'rss', 'escape'=> false
		)); ?></li>
	</ul>
</div>

<?php
$script = '
$(document).ready(function(){
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$html->scriptBlock($script, array('inline' => false));
?>

<div class="timeline index">
	<ul>
	<?php $i = 0; $prevDate = null;
		foreach ((array)$timeline as $event):
			$zebra = ($i++ % 2 == 0) ? 'zebra' : null;
			$type = $event['Timeline']['model'];
			if (!empty($event[$type])) {
				$date = $event[$type]['created'];
				$currentDate = date('l F d', strtotime($date));
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
		$paginator->options(array('url' => $this->passedArgs));
		echo $paginator->prev('<< ' . __('previous', true));
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next(__('next', true) . ' >>');
	?>
</div>
