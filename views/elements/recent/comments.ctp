<?php
if (!empty($comments)):

	$li = null;
	foreach ($comments as $comment) :

		$url = array('admin' => false,
			'controller' => 'tickets', 'action' => 'view', $comment['Ticket']['number'],
			'#' => 'c' . $comment['Comment']['id']
		);

		$project = null;
		if (!empty($comment['Ticket']['Project']) && $comment['Ticket']['Project']['id'] !== $CurrentProject->id) {
			$url = $chaw->url($comment['Ticket']['Project'], $url);
			$project = ' in '. $html->link($comment['Ticket']['Project']['name'], $chaw->url($comment['Ticket']['Project'], array(
				'admin' => false, 'controller' => 'source'
			)), array('class' => 'project'));
		}


		$li .= $html->tag('li', "on "
			. $html->link($comment['Ticket']['title'], $url) . " <small>({$comment['Ticket']['status']})</small> "
			. "by {$comment['User']['username']}" . $project
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', __('Recent Comments',true)) .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;