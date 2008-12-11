<?php
if (!empty($tickets)):

	$li = null;
	foreach ($tickets as $ticket) :

		$url = array('admin' => false,
			'controller' => 'tickets', 'action' => 'view', $ticket['Ticket']['number']
		);

		$project = null;
		if (!emptY($ticket['Project']) && $ticket['Project']['id'] !== $CurrentProject->id) {
			$url = $chaw->url($ticket['Project'], $url);
			$project = ' in '. $html->link($ticket['Project']['name'], $chaw->url($ticket['Project'], array(
				'admin' => false, 'controller' => 'browser'
			)), array('class' => 'project'));
		}

		$li .= $html->tag('li',
			$html->link($ticket['Ticket']['title'], $url) . $project
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Recent Tickets') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;