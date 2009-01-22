<?php
if (!empty($comments)):

	$li = null;
	foreach ($comments as $comment) :

		$url = array('admin' => false,
			'controller' => 'tickets', 'action' => 'view', $comment['Ticket']['id']
		);

		$project = null;
		if (!emptY($comment['Ticket']['Project']) && $comment['Ticket']['Project']['id'] !== $CurrentProject->id) {
			$url = $chaw->url($comment['Ticket']['Project'], $url);
			$project = ' in '. $html->link($comment['Ticket']['Project']['name'], $chaw->url($comment['Ticket']['Project'], array(
				'admin' => false, 'controller' => 'browser'
			)), array('class' => 'project'));
		}

		$li .= $html->tag('li', "on "
			. $html->link($comment['Ticket']['title'], $url) . " from {$comment['User']['username']}" . $project
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', __('Recent Comments',true)) .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;