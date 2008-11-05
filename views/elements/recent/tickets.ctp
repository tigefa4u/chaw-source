<?php
if (!empty($tickets)):

	$li = null;
	foreach ($tickets as $ticket) :

		$li .= $html->tag('li',
			$html->link($ticket['Commit']['id'], array('admin' => false,
				'controller' => 'tickets', 'action' => 'view', $ticket['Commit']['id']
			))
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Recent Tickets') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;