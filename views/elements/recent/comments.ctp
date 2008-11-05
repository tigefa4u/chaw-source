<?php
if (!empty($comments)):

	$li = null;
	foreach ($comments as $comment) :

		$li .= $html->tag('li',
			$html->link($comment['Ticket']['title'], array('admin' => false,
				'controller' => 'tickets', 'action' => 'view', $comment['Ticket']['id']
			))
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Recent Comments') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;